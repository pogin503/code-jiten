<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config/database.php';

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ExampleGroupMapper extends Eloquent
{

    protected $table = 't_example_group';

    public function __construct()
    {
    }

    public static function fetchLeaf()
    {
        return DB::table('t_example_group AS eg1')
            ->select(
                'eg1.group_cd', 'eg1.group_name', 
                'eg1.parent_id', 'eg2.group_name AS parent_name'
            )->join('t_example_group AS eg2', 'eg1.parent_id', '=', 'eg2.group_cd')
            ->where('eg1.disp_flag', '=', 1)
            ->orderBy('eg1.parent_id', 'asc')
            ->get();
    }

    public static function fetchChilds(int $group_cd)
    {
        $group_stmt = DB::getPdo()->prepare(
            "SELECT eg1.group_cd,
                eg1.group_name,
                eg1.\"desc\",
                eg1.disp_flag,
                eg1.parent_id,
                eg2.group_name AS parent_name
            FROM t_example_group eg1
            JOIN t_example_group eg2 ON eg1.parent_id = eg2.group_cd
            WHERE eg1.group_cd IN
                (SELECT group_descendant
                FROM t_example_relation
                WHERE group_ancestor = :group_cd)
            AND eg1.disp_flag = 1
            ORDER BY eg1.parent_id;"
        );
        $group_stmt->bindParam(':group_cd', $group_cd);
        $group_stmt->execute();
        return $group_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchParents(int $group_cd)
    {
        $group_stmt = DB::getPdo()->prepare(
            "SELECT group_cd,
                    group_name, \"desc\", disp_flag
            FROM t_example_group
            WHERE group_cd IN
                (SELECT group_ancestor
                FROM t_example_relation
                WHERE group_descendant = :group_cd1
                AND group_ancestor <> :group_cd2);"
        );
        $group_stmt->bindParam(':group_cd1', intval($group_cd));
        $group_stmt->bindParam(':group_cd2', intval($group_cd));
        $group_stmt->execute();
        return $group_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateGroup(
        int $group_cd, string $group_name, string $desc, 
        $disp_flag, int $parent_id
    ) {
        $group = DB::table($this->table)->where('group_cd', '=', $group_cd)->first();

        if (($parent_id == 0 || $parent_id == null) || $group->parent_id == $parent_id) {
            DB::table($this->table)
                ->where('group_cd', $group_cd)
                ->update(
                    [
                        'group_name' => $group_name,
                        'desc' => $desc,
                        'disp_flag' => $disp_flag,
                        // 'parent_id' => $parent_id
                    ]
                );
        } else {
            // group change process
            DB::beginTransaction();
            $delete_stmt = DB::getPdo()->prepare(
                "DELETE
                FROM t_example_relation
                WHERE group_descendant IN
                    (SELECT group_descendant
                    FROM t_example_relation
                    WHERE group_ancestor = :group_cd1)
                AND group_ancestor IN
                    (SELECT group_ancestor
                    FROM t_example_relation
                    WHERE group_descendant = :group_cd2
                    AND group_ancestor != group_descendant);"
            );
            $delete_stmt->bindValue(':group_cd1', $group_cd, PDO::PARAM_INT);
            $delete_stmt->bindValue(':group_cd2', $group_cd, PDO::PARAM_INT);
            $delete_stmt->execute();

            DB::table($this->table)
                ->where('group_cd', $group_cd)
                ->update(
                    [
                        'group_name' => $group_name,
                        'desc' => $desc,
                        'disp_flag' => $disp_flag,
                        'parent_id' => $parent_id
                    ]
                );
            // $this->insertGroup($group_cd, );
            $insert_stmt = DB::getPdo()->prepare(
                'INSERT INTO t_example_relation (group_ancestor, 
                    group_descendant, depth)
                SELECT supertree.group_ancestor,
                    subtree.group_descendant,
                    supertree.depth + subtree.depth + 1
                FROM t_example_relation AS supertree
                CROSS JOIN t_example_relation AS subtree
                WHERE supertree.group_descendant = :parent_id
                AND subtree.group_ancestor = :group_cd;'
            );
            $insert_stmt->bindValue(':parent_id', $parent_id, PDO::PARAM_INT);
            $insert_stmt->bindValue(':group_cd', $group_cd, PDO::PARAM_INT);
            $insert_stmt->execute();
            DB::commit();
        }

    }

    public function deleteGroup(array $group_cds)
    {
        DB::table('t_example_relation')
            ->whereIn('group_descendant', $group_cds)
            ->delete();

        DB::table($this->table)
            ->whereIn('group_cd', $group_cds)
            ->delete();
    }
    public function insertGroup(
        string $group_name, string $desc, $disp_flag, int $parent_group_cd
    ) {

        $isAllowedId = function ($id) {
            return $id >= 0;
        };
        if (!($disp_flag == 0 or $disp_flag == 1)
            || !$isAllowedId($parent_group_cd)
        ) {
            echo '登録できませんでした。';
            return;
        }
        DB::beginTransaction();
        $insert_stmt = DB::getPdo()->prepare(
            'INSERT INTO t_example_group (group_name, "desc", disp_flag, parent_id)
            VALUES (:group_name,
                :desc,
                :disp_flag,
                :parent_id) RETURNING group_cd;'
        );

        $parent_id = (is_null($parent_group_cd) ? 0 : $parent_group_cd);
        $insert_stmt->execute(
            [
                ':group_name' => $group_name,
                ':desc' => $desc,
                ':disp_flag' => $disp_flag,
                ':parent_id' => $parent_id,
            ]
        );
        $inserted_group_cd = $insert_stmt->fetchALL(PDO::FETCH_COLUMN);

        $insert_stmt2 = DB::getPdo()->prepare(
            'INSERT INTO t_example_relation (group_ancestor, group_descendant, depth)
            (SELECT t.group_ancestor,
                    cast(:group_cd1 AS integer),
                    depth + 1
            FROM t_example_relation AS t
            WHERE t.group_descendant = cast(:parent_id AS integer))
            UNION ALL
            (SELECT cast(:group_cd2 AS integer),
                    cast(:group_cd3 AS integer),
                    0);'
        );

        $insert_stmt2->bindValue(':group_cd1', $inserted_group_cd[0], PDO::PARAM_INT);
        $insert_stmt2->bindValue(':parent_id', $parent_id, PDO::PARAM_INT);
        $insert_stmt2->bindValue(':group_cd2', $inserted_group_cd[0], PDO::PARAM_INT);
        $insert_stmt2->bindValue(':group_cd3', $inserted_group_cd[0], PDO::PARAM_INT);
        $insert_stmt2->execute();
        DB::commit();
    }
}
