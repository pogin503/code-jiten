<?php
require_once(dirname(__FILE__) . '/../config/database.php');

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Language extends Eloquent {
    protected $table = 't_language';

    public static function getLanguage() {
        return self::query()->orderBy('language', 'asc')->get();
    }
}
