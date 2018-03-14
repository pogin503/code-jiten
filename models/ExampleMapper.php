<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../config/database.php');

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ExampleMapper extends Eloquent {
    protected $table = 't_example';
    public $timestamps = false;

    public function __construct() {
    }
    public function insertExample(string $language, string $example, int $group_cd){
        DB::table($this->table)
            ->insert(
                ['language' => $language,
                 'example' => $example,
                 'group_cd' => $group_cd]
        );
    }

    public function updateExample(string $language, string $example, int $example_id) {
        DB::table($this->table)
            ->where('example_id', '=', $example_id)
            ->update(
                ['language' => $language, 'example' => $example]
            );
    }

    public function deleteExample(int $example_id) {
        DB::table($this->table)
            ->where('example_id', '=', $example_id)
            ->delete();
    }
}
