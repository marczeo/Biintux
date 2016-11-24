<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver', function(Blueprint $table){
          $table->increments('id');
          $table->integer('user_id')->unsigned();
          $table->integer('route_id')->unsigned();
          $table->integer('concessioner_id')->unsigned();
        });

        Schema::table('driver', function(Blueprint $table){
          $table->foreign('user_id')->references('id')->on('users');
          $table->foreign('route_id')->references('id')->on('routes');
          $table->foreign('concessioner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver');
    }
}
