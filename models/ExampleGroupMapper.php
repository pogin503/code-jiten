<?php

require_once(dirname(__FILE__) . '/../config/database.php');

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ExampleGroupMapper extends Eloquent {

    public function __construct() {
    }

    public function getChild($group_cd){
//         $data = DB::select("
// SELECT group_cd, group_name, group_level, \"desc\", disp_flag
// FROM t_example_group
// where group_cd = :group_cd;", ['group_cd' => $group_cd]);
// 	return $data;
    }

    public function getParent(){

    }
    public function insertGroup($group_name, $group_level,
				$desc, $disp_flag, $parent_group_cd) {
	DB::beginTransaction();
	$insert_stmt = DB::getPdo()->prepare('
INSERT INTO t_example_group (group_name, group_level, "desc", disp_flag)
VALUES (:group_name, :group_level, :desc, :disp_flag) RETURNING group_cd;');
	$insert_stmt->execute(
		   [
		       ':group_name' => $group_name,
		       ':group_level' => $group_level,
		       ':desc' => $desc,
		       ':disp_flag' => $disp_flag,
		   ]);
	$inserted_group_cd = $insert_stmt->fetchALL(PDO::FETCH_COLUMN);

	$insert_stmt2 = DB::getPdo()->prepare('
INSERT INTO t_example_relation (group_ancestor, group_descendant)
  (SELECT t.group_ancestor, cast(:group_cd1 as integer)
  FROM t_example_relation AS t
  WHERE t.group_descendant = cast(:parent_group_cd as integer))
  UNION ALL
  (SELECT cast(:group_cd2 as integer), cast(:group_cd3 as integer));');

	$insert_stmt2->bindValue(':group_cd1', $inserted_group_cd[0], PDO::PARAM_INT);
	$insert_stmt2->bindValue(':parent_group_cd', $parent_group_cd, PDO::PARAM_INT);
	$insert_stmt2->bindValue(':group_cd2', $inserted_group_cd[0], PDO::PARAM_INT);
	$insert_stmt2->bindValue(':group_cd3', $inserted_group_cd[0], PDO::PARAM_INT);
	$insert_stmt2->execute();
	DB::commit();
    }
}
