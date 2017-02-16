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
        DB::table('roles')->insert(['description' => 'Common']);
        DB::table('roles')->insert(['description' => 'Driver']);
        DB::table('roles')->insert(['description' => 'Concessionaire']);
        DB::table('roles')->insert(['description' => 'Administrator']);
    }
}
