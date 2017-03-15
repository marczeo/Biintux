<?php

use Illuminate\Database\Seeder;
use App\Repositories\RouteRepository;
use App\Route;
class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $DAO = new RouteRepository;
        $newRoute= new Route;
        $newRoute->setColor();
        while ($DAO->existColor($newRoute->color)){
            $newRoute->setColor();
        }
        $newRoute->code="Test";
        $newRoute->encodepath="test";
        $newRoute->direction=1;
        $newRoute->first_run="5:00";
        $newRoute->last_run="22:00";
        $newRoute->type="bus";
        $newRoute->save();
    }
}
