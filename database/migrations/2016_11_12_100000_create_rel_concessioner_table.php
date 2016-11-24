<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelConcessionerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('rel_concessioner', function(Blueprint $table){
        $table->increments('id');
        $table->integer('concessioner_id')->unsigned();
        $table->integer('route_id')->unsigned();
      });

      Schema::table('rel_concessioner', function(Blueprint $table){
        $table->foreign('concessioner_id')->references('id')->on('users');
        $table->foreign('route_id')->references('id')->on('routes');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_concessioner');
    }
}
