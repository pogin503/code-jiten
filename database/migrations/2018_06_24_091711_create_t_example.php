<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTExample extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example', function (Blueprint $table) {
            $table->increments('example_id');
            $table->integer('language_id');
            $table->string('language', 50);
            $table->text('example');
            $table->integer('group_cd');
            $table->foreign('language_id')
                ->references('id')->on('language')
                ->onDelete('cascade')
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
        Schema::dropIfExists('example');
    }
}
