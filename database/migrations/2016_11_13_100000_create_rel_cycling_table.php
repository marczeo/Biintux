<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelCyclingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_cycling', function(Blueprint $table){
          $table->increments('id');
          $table->integer('cycling_route_id')->unsigned();
          $table->integer('node1_id')->unsigned();
          $table->integer('node2_id')->unsigned();
        });

        Schema::table('rel_cycling', function(Blueprint $table){
          $table->foreign('cycling_route_id')->references('id')->on('cycling_routes');
          $table->foreign('node1_id')->references('id')->on('nodes');
          $table->foreign('node2_id')->references('id')->on('nodes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_cycling');
    }
}
