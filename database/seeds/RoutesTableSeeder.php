<?php

use Illuminate\Database\Seeder;
use App\Repositories\RouteRepository;
use App\Route;
use App\Path;
class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $directory_routes = database_path("/seeds/dataForSeed/routes");
        $directory_decode = database_path("/seeds/dataForSeed/decodePaths");
        $routes_files = File::allFiles($directory_routes);
        foreach ($routes_files as $route_file)
        {
            $route_json = File::get((string)$route_file);
            $route=json_decode($route_json);
            
            $DAO = new RouteRepository;
            $newRoute= new Route;
            $path_ida = new Path;
            $path_vuelta = new Path;

            $newRoute->setColor();
            while ($DAO->existColor($newRoute->color)){
                $newRoute->setColor();
            }

            $newRoute->first_run="5:00";
            $newRoute->last_run="22:00";
            $newRoute->type="bus";
            $newRoute->name=$route->name;
            $newRoute->save();

            
            $decodePath1=File::get($directory_decode."/".$route->id."_decodedPath.txt");
            $path_ida->encodepath=$decodePath1;
            $path_ida->direction=1;
            $path_ida->route_id=$newRoute->id;
            $path_ida->save();
           
            

            $decodePath2=File::get($directory_decode."/".$route->id."_decodedPath2.txt");
            $path_vuelta->encodepath=$decodePath2;
            $path_vuelta->direction=2;
            $path_vuelta->route_id=$newRoute->id;
            $path_vuelta->save();
            
        }
    }
}
