<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_route', function(Blueprint $table){
          $table->increments('id');
          $table->integer('route_id')->unsigned();
          $table->integer('start_node_id')->unsigned();
          $table->integer('next_node_id')->unsigned()->nullable();
        });

        Schema::table('rel_route', function(Blueprint $table){
          $table->foreign('route_id')->references('id')->on('routes');
          $table->foreign('start_node_id')->references('id')->on('nodes');
          $table->foreign('next_node_id')->references('id')->on('nodes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_route');
    }
}
