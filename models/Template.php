<?php
require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../config/database.php');

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Template extends Eloquent
{
    protected $table = 't_language_template';
    public function getWithLanguage()
    {
        return DB::table('t_language_template AS t1')
            ->join('t_language AS t2', 't1.language_id', '=', 't2.id')
            ->select('t1.id', 't1.language_id', 't2.language', 't1.template')
            ->orderBy('t2.language', 'asc')
            ->get();
    }
    public function updateTemplate(int $language_id, string $template)
    {
        self::where('language_id', $language_id)->update([
            'template' => $template
        ]);
    }
}
