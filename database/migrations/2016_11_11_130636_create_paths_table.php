<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paths', function(Blueprint $table){
            $table->increments('id');
            $table->integer('route_id')->unsigned();
            $table->integer('direction');
            $table->mediumText('encodepath');

            //Foreign key
            $table->foreign('route_id')
                ->references('id')->on('routes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paths');
    }
}
