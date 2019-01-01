<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVExampleDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
        CREATE VIEW public.v_example_desc AS
        SELECT example.example_id,
           example.example,
           example.language_id,
           language.language,
           example_group.group_cd,
           example_group.group_name,
           example_group."desc"
          FROM ((public.t_example example
            JOIN public.t_example_group example_group ON ((example.group_cd = example_group.group_cd)))
            JOIN public.t_language language ON ((language.id = example.language_id)));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS public.v_example_desc;');
    }
}
