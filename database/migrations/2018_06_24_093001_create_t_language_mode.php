<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTLanguageMode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_mode', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('language_id');
            $table->integer('mode_id');
            $table->foreign('language_id')
                ->references('id')->on('language')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('mode_id')
                ->references('id')->on('syntax_mode')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_mode');
    }
}
