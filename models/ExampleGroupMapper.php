<?php

require_once(dirname(__FILE__) . '/../config/database.php');

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ExampleGroupMapper extends Eloquent {

    protected $table = 't_example_group';

    public function __construct() {
    }

    public static function fetchLeaf() {
        return self::query()
            ->select('group_cd', 'group_name')
            ->where('disp_flag', '=', 1)
            ->get();
    }

    public function getChild($group_cd){
        //         $data = DB::select("
        // SELECT group_cd, group_name, group_level, \"desc\", disp_flag
        // FROM t_example_group
        // where group_cd = :group_cd;", ['group_cd' => $group_cd]);
        // 	return $data;
    }

    public static function fetchParents($group_cd) {
        $group_stmt = DB::getPdo()->prepare("SELECT group_cd, group_name, \"desc\", disp_flag FROM t_example_group WHERE group_cd IN
(SELECT group_ancestor FROM t_example_relation WHERE group_descendant = :group_cd1 AND group_ancestor <> :group_cd2);");
        $group_stmt->bindParam(':group_cd1', intval($group_cd));
        $group_stmt->bindParam(':group_cd2', intval($group_cd));
        $group_stmt->execute();
        return $group_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getParent(){

    }

    public function updateGroup($group_cd, $group_name, $desc, $disp_flag, $parent_id) {
        DB::table('t_example_group')
            ->where('group_cd', $group_cd)
            ->update([
                'group_name' => $group_name,
                'desc' => $desc,
                'disp_flag' => $disp_flag,
                // 'parent_id' => $parent_id
            ]);
    }

    public function deleteGroup($group_cds) {
        DB::table('t_example_relation')
            ->whereIn('group_descendant', $group_cds)
            ->delete();

        DB::table('t_example_group')
            ->whereIn('group_cd', $group_cds)
            ->delete();
    }
    public function insertGroup($group_name, $desc, $disp_flag, $parent_group_cd) {

        $isAllowedId = function ($id) {
            return $id >= 0;
        };
        if (!($disp_flag == 0 or $disp_flag == 1)
            || !$isAllowedId($parent_group_cd)) {
            echo '登録できませんでした。';
            return;
        }
        $insert_stmt = DB::getPdo()->prepare('
INSERT INTO t_example_group (group_name, "desc", disp_flag, parent_id)
VALUES (:group_name, :desc, :disp_flag, :parent_id) RETURNING group_cd;');
        $insert_stmt->execute(
            [
                ':group_name' => $group_name,
                ':desc' => $desc,
                ':disp_flag' => $disp_flag,
                ':parent_id' => $parent_group_cd,
            ]);
        $inserted_group_cd = $insert_stmt->fetchALL(PDO::FETCH_COLUMN);

        $insert_stmt2 = DB::getPdo()->prepare('
INSERT INTO t_example_relation (group_ancestor, group_descendant, depth)
  (SELECT t.group_ancestor, cast(:group_cd1 as integer), depth + 1
  FROM t_example_relation AS t
  WHERE t.group_descendant = cast(:parent_group_cd as integer))
  UNION ALL
  (SELECT cast(:group_cd2 as integer), cast(:group_cd3 as integer), 0);');

        $insert_stmt2->bindValue(':group_cd1', $inserted_group_cd[0], PDO::PARAM_INT);
        $insert_stmt2->bindValue(':parent_group_cd', $parent_group_cd, PDO::PARAM_INT);
        $insert_stmt2->bindValue(':group_cd2', $inserted_group_cd[0], PDO::PARAM_INT);
        $insert_stmt2->bindValue(':group_cd3', $inserted_group_cd[0], PDO::PARAM_INT);
        $insert_stmt2->execute();
        DB::commit();
    }
}