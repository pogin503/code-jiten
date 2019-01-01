<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTExampleGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_group', function (Blueprint $table) {
            $table->increments('group_cd');
            $table->string('group_name', 255);
            $table->string('desc', 400);
            $table->smallInteger('disp_flag');
            $table->integer('parent_id');
            $table->timestamps();
        });
        Schema::table('example', function (Blueprint $table) {
            $table->foreign('group_cd')
                ->references('group_cd')->on('example_group')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('example', function (Blueprint $table) {
            $table->dropForeign(['group_cd']);
        });
        Schema::dropIfExists('example_group');

    }
}
