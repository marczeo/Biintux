<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Route;
use App\Rel_route;
use App\Node;
use App\Path;
use Illuminate\Support\Collection;
use DB;
class RouteRepository
{
    /**
     * The type node associated with the controller.
     *
     * @var string
     */

    protected $typeNode = 'route';


    /**
     * Get all of the bus routes
     *
     * @param  null
     * @return Collection
     */
    public function getAllRoutesID()
    {
        $ids =Route::orderBy('id','asc')->select('id')->get();
        return $ids;
    }

    /**
     * Get all of the bus routes
     *
     * @param  null
     * @return json
     */
    public function getAllRoutes($type = null)
    {
        $routes =Route::orderBy('id','asc')->get();
        if($type!=null)
            $routes =$routes->where('type',$type);
        
        
        $route_response=new Collection;
        foreach ($routes as $key=> $route)
        {
            $route_array=[];
            $nodos = new Collection;
            /*foreach ($route->rel_route as $key => $rel_route) {
                $nodo=[];
                $nodo['longitude']=$rel_route->start_node->longitude;
                $nodo['latitude']=$rel_route->start_node->latitude;
                $nodos->push($nodo);
            }*/
            $route_array['id']=$route->id;
            $route_array['name']=$route->name;
            $route_array['type']=$route->type;
            $route_array['type_read']=trans('route.'.$route->type);
            $route_array['color']=$route->color;
            //$route_array['nodos']=$nodos;
            $route_array['paths']=$route->paths;
            $route_response->push($route_array);
        }
        $response['data']=$route_response;
        return json_encode($response);
    }
    /**
     * Create a new route
     *
     * @param  Request $request
     * @return Boolean
     */
    public function createRoute(Request $request)
    {
        $route = new route;
        $route->setColor();
        while ($this->existColor($route->color)){
            $route->setColor();
        }

        $route->name=$request->name;
        $route->encodepath=$request->encodePath;
        $route->direction=1;
        $route->first_run=$request->first_run;
        $route->last_run=$request->last_run;
        $route->type=$request->type_route;
        

         /*Limpieza de nodos*/
        preg_match_all('/\((.*?)\)/', $request->markerList, $nodes);
        $primeraPasada=true;
        foreach ($nodes[1] as $node) {
            $latLong=explode (",", $node);
            $newNode= new Node;
            $newNode->latitude=$latLong[0];
            $newNode->longitude=$latLong[1];
            $newNode->type=$this->typeNode;
            $newNode->save();

            if($primeraPasada)
            {
                $primeraPasada=false;
                $route->start_node_id=$newNode->id;
                $route->save();
            }
            else{
                $rel_route->next_node_id=$newNode->id;
                $rel_route->save();
            }
            $rel_route = new Rel_route;
            $rel_route->route_id = $route->id;
            $rel_route->start_node_id=$newNode->id;

        }
        $rel_route->save();
        return true;
    }

    /**
     * Delete a route
     *
     * @param  int $IDroute
     * @return boolean
     */
    public function deleteRoute($IDroute)
    {
        try{
            $route=Route::findOrFail($IDroute);
            foreach ($route->rel_route as $key => $rel_route) {
                $rel_route->delete();
            }
            $route->delete();
            return true;
        }
        catch(ModelNotFoundException $e)
        {
            //dd(get_class_methods($e)); // lists all available methods for exception object
            return false;
        }
    }

    /**
     * Verify that color exist
     *
     * @param  string  $color
     * @return boolean
     */
    public function existColor($color)
    {
        
        if(Route::where('color', $color)->count())
            return true;
        return false;

    }

    /**
     * calcular distancia entre dos coordenadas
     *
     * @param double latitude1
     * @param double longitude1
     * @param double latitude2
     * @param double longitude2
     * @return double distance
     */
    public function distanceCalculation($latitude1, $longitude1, $latitude2, $longitude2, $decimals = 2)
    {
        $degrees = rad2deg(acos((sin(deg2rad($latitude1))*sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1))*cos(deg2rad($latitude2))*cos(deg2rad($longitude1-$longitude2)))));

        $distance = ($degrees * 111)*1000; // 1 grado = 111.13384 km, basándose en el diametro promedio de la Tierra (12.735 km)
        return round($distance, $decimals);
    }

    /**
     * Obtener rutas cercanas a partir de los nodos
     *
     * @param double latitude
     * @param double longitude
     * @param decimal rango
     * @return Collection rutas
     */
    public function nearRoutesNodes($latitude, $longitude, $rango)
    {
        $rutasID = $this->getAllRoutesID();
        $routes_total= $rutasID->count();
        $nearID_array = array();
        for ($index_rutasID=0; $index_rutasID < $routes_total; $index_rutasID++) { 
            $ruta_nodos = Route::findOrFail($rutasID[$index_rutasID]->id)->rel_route;
            foreach ($ruta_nodos as $nodo) {
                if($this->distanceCalculation($nodo->start_node->latitude,$nodo->start_node->longitude,$latitude,$longitude) <= $rango)
                {
                    array_push($nearID_array,$rutasID[$index_rutasID]->id);
                    break;
                }
            }

        }

               
        
        
        $routes= Route::whereIn('id', $nearID_array)
                    ->get();
        $route_response=new Collection;
        foreach ($routes as $key=> $route)
        {
            $route_array=[];
            $route_array['id']=$route->id;
            $route_array['name']=$route->name;
            $route_array['type']=$route->type;
            $route_array['type_read']=trans('route.'.$route->type);
            //$route_array['name']=$route->name;
            $route_array['paths']=$route->paths;
            $route_array['color']=$route->color;
            $route_response->push($route_array);
        }
        $response['data']=$route_response;
        
        return $response;
    }

    /**
     * Obtener rutas cercanas a partir
     *
     * @param double latitude
     * @param double longitude
     * @param decimal rango
     * @return Collection rutas
     */
    public function nearRoutes($latitude, $longitude, $rango)
    {
        $rutasID = $this->getAllRoutesID();
        $routes_total= $rutasID->count();
        $nearID_array = array();
        for ($index_rutasID=0; $index_rutasID < $routes_total; $index_rutasID++) { 
            $ruta_nodos = Route::findOrFail($rutasID[$index_rutasID]->id)->paths;
            foreach ($ruta_nodos as $ruta) {
                $poly_nodes =  \GeometryLibrary\PolyUtil::decode($ruta->encodepath);
                
                if(\GeometryLibrary\PolyUtil::isLocationOnEdge(
                    ['lat' => $latitude, 'lng'=> $longitude],
                    $poly_nodes,
                    $rango))
                {
                    array_push($nearID_array,$rutasID[$index_rutasID]->id);
                    break;
                }
            }

        }

        
        
        $routes= Route::whereIn('id', $nearID_array)
                    ->get();
        $route_response=new Collection;
        foreach ($routes as $key=> $route)
        {
            $route_array=[];
            $route_array['id']=$route->id;
            $route_array['name']=$route->name;
            $route_array['type']=$route->type;
            $route_array['type_read']=trans('route.'.$route->type);
            //$route_array['name']=$route->name;
            $route_array['paths']=$route->paths;
            $route_array['color']=$route->color;
            $route_response->push($route_array);
        }
        $response['data']=$route_response;
        
        return $response;
    }
}