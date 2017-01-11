<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelStopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('rel_stop', function(Blueprint $table){
        $table->increments('id');
        $table->integer('route_id')->unsigned();
        $table->integer('stop_node_id')->unsigned();
      });

      Schema::table('rel_stop', function(Blueprint $table){
        $table->foreign('route_id')->references('id')->on('routes')
          ->onDelete('restrict')->onUpdate('cascade');
        $table->foreign('stop_node_id')->references('id')->on('nodes')
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
        Schema::dropIfExists('rel_stop');
    }
}
