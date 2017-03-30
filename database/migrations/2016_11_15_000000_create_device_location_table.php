<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('device_location', function(Blueprint $table){
        $table->increments('id');
        $table->integer('device_id')->unsigned()->nullable();
        $table->integer('user_id')->unsigned()->nullable();
        $table->float('longitude', 10, 6);
        $table->float('latitude', 10, 6);
      });

      Schema::table('device_location', function(Blueprint $table){
        $table->foreign('user_id')->references('id')->on('users')
          ->onDelete('restrict')->onUpdate('cascade');
         $table->foreign('device_id')->references('id')->on('devices')
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
        Schema::dropIfExists('device_location');
    }
}
