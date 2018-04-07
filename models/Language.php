<?php
require_once(dirname(__FILE__) . '/../config/database.php');

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Language extends Eloquent {
    protected $table = 't_language';

    public static function getWithTemplate() {
        return DB::table('t_language AS t1')
            ->join('t_language_template AS t2', 't1.id', '=', 't2.language_id')
            ->select('t1.id AS language_id', 't1.language', 't2.id AS template_id', 't2.template')
            ->orderBy('t1.language', 'asc')
            ->get();
    }

    public static function getLanguage() {
        return self::query()->orderBy('language', 'asc')->get();
    }
}
