<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(['description' => 'Common user']);
        DB::table('roles')->insert(['description' => 'Driver user']);
        DB::table('roles')->insert(['description' => 'Concessioner user']);
        DB::table('roles')->insert(['description' => 'Administrator user']);
    }
}
