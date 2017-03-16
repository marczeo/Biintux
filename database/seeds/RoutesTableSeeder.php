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
        $directory = database_path("/rutas");
        $directory_decode = database_path("/decode");
        $files = File::allFiles($directory);
        foreach ($files as $file)
        {
            $json = File::get((string)$file);
            $obj=json_decode($json);
            
            $DAO = new RouteRepository;
            $newRoute= new Route;
            $newRoute2=new Route;

            $newRoute->setColor();
            while ($DAO->existColor($newRoute->color)){
                $newRoute->setColor();
            }

            $decode1=File::get($directory_decode."/".$obj->id."_decodedPath.txt");
            $newRoute->code=$obj->name;
            $newRoute->encodepath=$decode1;
            $newRoute->direction=1;
            $newRoute->first_run="5:00";
            $newRoute->last_run="22:00";
            $newRoute->type="bus";
            $newRoute->save();

            $decode2=File::get($directory_decode."/".$obj->id."_decodedPath2.txt");
            $newRoute2->code=$newRoute->code;
            $newRoute2->encodepath=$decode2;
            $newRoute2->direction=2;
            $newRoute2->first_run="5:00";
            $newRoute2->last_run="22:00";
            $newRoute2->type="bus";
            $newRoute2->color=$newRoute->color;
            $newRoute2->save();
            
        }
    }
}
