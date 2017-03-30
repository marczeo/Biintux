<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        //Admin
        $faker->seed(0);
        factory(App\User::class)->create([
          'role_id'=>App\Role::where('description', 'Administrator')->value('id'),
          'email'=>'admin@biintux.com',
        ]);
    }
}
