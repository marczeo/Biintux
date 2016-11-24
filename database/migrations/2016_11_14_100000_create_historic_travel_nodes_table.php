<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricTravelNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('historic_travel_nodes', function(Blueprint $table){
        $table->increments('id');
        $table->string('type');
        $table->string('code');
        $table->integer('historic_id')->unsigned();
        $table->integer('next_historic_travel_nodes_id')->unsigned();
        $table->float('longitude', 10, 6);
        $table->float('latitude', 10, 6);
      });

      Schema::table('historic_travel_nodes', function(Blueprint $table){
        $table->foreign('historic_id')->references('id')->on('historic_travel');
        $table->foreign('next_historic_travel_nodes_id')->references('id')->on('historic_travel_nodes');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historic_travel_nodes');
    }
}
