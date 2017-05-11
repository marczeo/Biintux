<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('password');
            $table->string('name');
            $table->date('birthdate')->nullable();
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->integer('role_id')->unsigned();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function(Blueprint $table){
          $table->foreign('role_id')->references('id')->on('roles')
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
        Schema::dropIfExists('users');
    }
}
