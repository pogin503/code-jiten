<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTExampleRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_relation', function (Blueprint $table) {
            $table->integer('group_ancestor');
            $table->integer('group_descendant');
            $table->integer('depth');
            $table->foreign('group_ancestor')
                ->references('group_cd')->on('example_group')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('group_descendant')
                ->references('group_cd')->on('example_group')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('example_relation');
    }
}
