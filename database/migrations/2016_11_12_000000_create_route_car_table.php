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
          $table->integer('concessionaire_id')->unsigned();
          $table->integer('passenger_capacity')->default(0);
          $table->boolean('enabled')->default(true);
        });

        Schema::table('route_car', function(Blueprint $table){
          $table->foreign('concessionaire_id')->references('id')->on('users')
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
