<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_car', function(Blueprint $table){
          $table->increments('id');
          $table->string('economic_number');
          $table->integer('route_id')->unsigned();
        });

        Schema::table('route_car', function(Blueprint $table){
          $table->foreign('route_id')->references('id')->on('routes')
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
        Schema::dropIfExists('route_car');
    }
}
