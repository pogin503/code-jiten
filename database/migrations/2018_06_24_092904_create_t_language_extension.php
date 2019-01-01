<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTLanguageExtension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_extension', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('language_id');
            $table->string('extension', 10);
            $table->smallInteger('default_extension')
                ->default(0);
            $table->foreign('language_id')
                ->references('id')->on('language')
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('language_extension');
        Schema::enableForeignKeyConstraints();

    }
}
