<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function(Blueprint $table){
          $table->increments('id');
          $table->string('code');
          $table->integer('start_node_id')->unsigned();
          $table->integer('direction');
          $table->time('first_run');
          $table->time('last_run');
          $table->mediumText('encodepath');
          $table->string('color');
          $table->string('type')->default("bus");
        });

        Schema::table('routes', function(Blueprint $table){
          $table->foreign('start_node_id')->references('id')->on('nodes')
            ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
