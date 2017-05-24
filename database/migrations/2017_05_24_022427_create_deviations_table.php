<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deviations', function(Blueprint $table){
            $table->increments('id');
            $table->integer('route_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('deviations', function(Blueprint $table){
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
        Schema::dropIfExists('deviations');
    }
}
