<?php

use Illuminate\Database\Seeder;
use App\Repositories\RouteRepository;
use App\Route;
use App\Path;
use App\Node;
use App\Rel_route;
class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $directory_routes   = database_path("/seeds/dataForSeed/routes");
        $directory_decode   = database_path("/seeds/dataForSeed/decodePaths");
        $directory_nodes    = database_path("/seeds/dataForSeed/latlng");

        #Read all files with routes
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

            #Create new route
            $newRoute->first_run="5:00";
            $newRoute->last_run="22:00";
            $newRoute->type="bus";
            $newRoute->name=$route->name;
            $newRoute->save();

            #Read file with first path and Create first path
            $decodePath1=File::get($directory_decode."/".$route->id."_decodedPath.txt");
            $path_ida->encodepath=$decodePath1;
            $path_ida->direction=1;
            $path_ida->route_id=$newRoute->id;
            $path_ida->save();
           
            #Read file with second path and Create second path
            $decodePath2=File::get($directory_decode."/".$route->id."_decodedPath2.txt");
            $path_vuelta->encodepath=$decodePath2;
            $path_vuelta->direction=2;
            $path_vuelta->route_id=$newRoute->id;
            $path_vuelta->save();
            
            #Read latlng (nodes) from each route
            $directory_path_ida_nodes_file=$directory_nodes."/".$route->id."-1_json.txt";
            if (File::exists($directory_path_ida_nodes_file))
            {
                $path_ida_nodes_json = File::get((string)$directory_path_ida_nodes_file);
                $path_ida_nodes=json_decode($path_ida_nodes_json)->coordinates;

                foreach ($path_ida_nodes as $path_ida_node) {
                    $newNode= new Node;
                    $newNode->longitude = $path_ida_node->lng;
                    $newNode->latitude = $path_ida_node->lat;
                    $newNode->type='route';
                    $newNode->save();

                    $newRel= new Rel_route;
                    $newRel->start_node_id=$newNode->id;
                    $newRel->save();
                    //echo $path_ida_node->lat.", ".$path_ida_node->lng."\n";
                }
            }

            $directory_path_vuelta_nodes_file=$directory_nodes."/".$route->id."-2_json.txt";
            if (File::exists($directory_path_vuelta_nodes_file))
            {
                $path_vuelta_nodes_json = File::get((string)$directory_path_vuelta_nodes_file);
                $path_vuelta_nodes=json_decode($path_vuelta_nodes_json)->coordinates;

                foreach ($path_vuelta_nodes as $path_vuelta_node) {
                    $newNode= new Node;
                    $newNode->longitude = $path_vuelta_node->lng;
                    $newNode->latitude = $path_vuelta_node->lat;
                    $newNode->type='route';
                    $newNode->save();

                    $newRel= new Rel_route;
                    $newRel->start_node_id=$newNode->id;
                    $newRel->save();
                    //echo $path_vuelta_node->lat.", ".$path_vuelta_node->lng."\n";
                }
            }
        }
    }
}
