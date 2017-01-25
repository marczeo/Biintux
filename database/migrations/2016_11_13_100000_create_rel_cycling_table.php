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
          $table->integer('start_node_id')->unsigned();
          $table->integer('next_node_id')->unsigned()->nullable();
        });

        Schema::table('rel_cycling', function(Blueprint $table){
          $table->foreign('cycling_route_id')->references('id')->on('cycling_routes')
            ->onDelete('restrict')->onUpdate('cascade');
          $table->foreign('start_node_id')->references('id')->on('nodes')
            ->onDelete('restrict')->onUpdate('cascade');
          $table->foreign('next_node_id')->references('id')->on('nodes')
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
        Schema::dropIfExists('rel_cycling');
    }
}
