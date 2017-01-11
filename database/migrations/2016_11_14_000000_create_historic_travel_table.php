<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricTravelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historic_travel', function(Blueprint $table){
          $table->increments('id');
          $table->integer('users_id')->unsigned();
          $table->dateTime('date_time');
          $table->bigInteger('lapsed_time');//lapsed time in seconds
          $table->boolean('is_travel');//if is a user travel or a driver travel
        });

        Schema::table('historic_travel', function(Blueprint $table){
          $table->foreign('users_id')->references('id')->on('users')
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
        Schema::dropIfExists('historic_travel');
    }
}
