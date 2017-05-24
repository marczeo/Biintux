<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviationNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deviation_nodes', function(Blueprint $table){
            $table->increments('id');
            $table->integer('deviation_id')->unsigned()->nullable();
            $table->float('longitude', 10, 6);
            $table->float('latitude', 10, 6);
        });

        Schema::table('deviation_nodes', function(Blueprint $table){
            $table->foreign('deviation_id')->references('id')->on('deviations')
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
        Schema::dropIfExists('deviation_nodes');
    }
}
