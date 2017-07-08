<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Route;
use App\Rel_route;
use App\Node;
use App\Path;
use Illuminate\Support\Collection;
use DB;
use Illuminate\Database\QueryException;
use App\GeometryLibrary\PolyUtil;
use App\GeometryLibrary\SphericalUtil;
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
     * @param  strgin $type
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
            $paths = new Collection;
            foreach ($route->paths as $path) {
                $nodos = new Collection;
                $path_array=[];
                /*foreach ($path->rel_route as $rel_route) {
                    $nodo=[];
                    $nodo['longitude']=$rel_route->start_node->longitude;
                    $nodo['latitude']=$rel_route->start_node->latitude;
                    $nodos->push($nodo);
                }*/
                $path_array['id']=$path->id;
                $path_array['route_id']=$path->route_id;
                $path_array['direction']=$path->direction;
                $path_array['encodepath']=$path->encodepath;
                $path_array['nodos']=$nodos;

                $paths->push($path_array);
                //return $path_array;
            }
            
            $route_array['id']=$route->id;
            $route_array['name']=$route->name;
            $route_array['type']=$route->type;
            $route_array['type_read']=trans('route.'.$route->type);
            $route_array['color']=$route->color;
            $route_array['paths']=$paths;
            $route_response->push($route_array);
        }
        $response['data']=$route_response;
        return $response;
    }

    /**
     * Obtener los nodos de una ruta
     * @param Route $route
     * @return json
    */
    public function getRouteNodes($route)
    {
        try {
            $paths = new Collection;
            foreach ($route->paths as $path) {
                $nodos = new Collection;
                $path_array=[];
                foreach ($path->rel_route as $rel_route) {
                    $nodo=[];
                    $nodo['longitude']=$rel_route->start_node->longitude;
                    $nodo['latitude']=$rel_route->start_node->latitude;
                    $nodos->push($nodo);
                }
                $path_array['id']=$path->id;
                $path_array['route_id']=$path->route_id;
                $path_array['direction']=$path->direction;
                $path_array['encodepath']=$path->encodepath;
                $path_array['nodos']=$nodos;

                $paths->push($path_array);
            }
            $response['data']=$paths;
            return json_encode($response);
            //return response()->json(['code'=>200, 'response'=>'Status changed successfully'],200);
        } catch (QueryException $e) {
            return response()->json(['code'=>400 ,'response'=>'An error has occurred'],400);
        }
    }

    /**
     * Create a new route
     *
     * @param  Request $request
     * @return Boolean
     */
    public function createRoute(Request $request)
    {
        $route = new Route;
        $path = new Path;
        $route->setColor();
        while ($this->existColor($route->color)){
            $route->setColor();
        }

        $route->name=$request->name;
        $route->first_run=$request->first_run;
        $route->last_run=$request->last_run;
        $route->type=$request->type_route;
        $route->save();
        
        $path->encodepath=$request->encodePath;
        $path->direction=1;
        $path->route_id=$route->id;

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
                $path->save();
            }
            else{
                $rel_route->next_node_id=$newNode->id;
                $rel_route->save();
            }
            $rel_route = new Rel_route;
            $rel_route->path_id = $path->id;
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
            foreach ($route->paths as $path) {
                foreach ($path->rel_route as $key => $rel_route) {
                    $rel_route->delete();
                }
                $path->delete();
            }
            
            $route->delete();
            return true;
        }
        catch(QueryException $e)
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
            $paths = Route::findOrFail($rutasID[$index_rutasID]->id)->paths;
            foreach ($paths as $path) {
                foreach ($path->rel_route as $nodo) {
                    if($this->distanceCalculation($nodo->start_node->latitude,$nodo->start_node->longitude,$latitude,$longitude) <= $rango)
                    {
                        array_push($nearID_array,$rutasID[$index_rutasID]->id);
                        break;
                    }
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
            $route_array['paths']=$route->paths;
            $route_array['color']=$route->color;
            $route_response->push($route_array);
        }
        $response['data']=$route_response;
        
        return $response;
    }

    /**
     * Obtener rutas cercanas a partir
     * https://github.com/alexpechkarev/geometry-library#isLocationOnEdge
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
                $poly_nodes =  PolyUtil::decode($ruta->encodepath);
                
                if(PolyUtil::isLocationOnPath(
                    ['lat' => $latitude, 'lng'=> $longitude],
                    $poly_nodes,
                    $rango))
                {
                    array_push($nearID_array,$rutasID[$index_rutasID]->id);
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
        //$response['data']=$route_response;
        
        return $route_response;
    }

    /**
     * Obtener rutas cercanas a partir
     * https://github.com/alexpechkarev/geometry-library#isLocationOnEdge
     * @param double latitude_origen
     * @param double longitude_oirgen
     * @param double latitude_destino
     * @param double longitude_destino
     * @param decimal rango
     * @return Collection rutas
     */
    public function customRoutes($latitude_origen, $longitude_origen, $latitude_destino, $longitude_destino, $rango)
    {
        $rutasID = $this->getAllRoutesID();
        $routes_total= $rutasID->count();

        $near_origin = new Collection;
        $near_destiny= new Collection;
        $near_merge= new Collection;

        #Recorrer todas las rutas
        for ($index_rutasID=0; $index_rutasID < $routes_total; $index_rutasID++) { 
            $ruta_derroteros = Route::findOrFail($rutasID[$index_rutasID]->id)->paths;
            #Recorrer cada derrotero (Usualmente son dos por ruta)
            foreach ($ruta_derroteros as $derrotero) {
                $derrotero_nodes = PolyUtil::decode($derrotero->encodepath);

                #Si hay ruta cercana al nodo origen: array con lat,lng. Sino false
                $temporal_origin=PolyUtil::isLocationOnPath_custom(
                    ['lat' => $latitude_origen, 'lng'=> $longitude_origen],
                    $derrotero_nodes,
                    $rango);
                #Validar que haya ruta cercana al origen y que no se haya guardado dicha ruta
                if($temporal_origin && !$near_origin->contains('path_id', $derrotero->id))
                {
                    #Agregar id_ruta y el nodo de la ruta cercano al punto de origen
                    $near_node=[];
                    $near_node['route_id']=$rutasID[$index_rutasID]->id;
                    $near_node['path_id']=$derrotero->id;
                    $near_node['lat']=$temporal_origin['lat'];
                    $near_node['lng']=$temporal_origin['lng'];
                    $near_origin->push($near_node);
                    
                }

                #Si hay ruta cercana al nodo de destino: array con lat,lng. Sino false
                $temporal_destiny=PolyUtil::isLocationOnPath_custom(
                    ['lat' => $latitude_destino, 'lng'=> $longitude_destino],
                    $derrotero_nodes,
                    $rango);
                #Validar que exista ruta cercana al destino y no se haya guardado dicha rua
                if($temporal_destiny && !$near_destiny->contains('path_id', $derrotero->id))
                {
                    #Agregar id_ruta y el nodo de la ruta cercano al punto de destino
                    $near_node=[];
                    $near_node['route_id']=$rutasID[$index_rutasID]->id;
                    $near_node['path_id']=$derrotero->id;
                    $near_node['lat']=$temporal_destiny['lat'];
                    $near_node['lng']=$temporal_destiny['lng'];
                    $near_destiny->push($near_node);
                    
                }
            }
        }

        #Encontrar rutas que se encuentren cerca tanto de origen como destino. Indica que un solo camión hace el recorrido
        foreach ($near_origin as $value) {
            if($near_destiny->contains('route_id', $value['route_id']) && $near_destiny->contains('path_id', $value['path_id']))
            {

                $destino_tmp=$near_destiny->where('path_id',$value['path_id'])->all();
                $destino_array=[];
                foreach ($destino_tmp as $temp) {
                    $destino_array['route_id']=$temp['route_id'];
                    $destino_array['lat']=$temp['lat'];
                    $destino_array['lng']=$temp['lng'];
                }
                
                $newMerge=[];
                $newMerge['route_id']=$value['route_id'];
                $newMerge['nodos']=['origen'=>['lat'=>$value['lat'], 'lng'=>$value['lng']],
                                    'destino'=>['lat'=>$destino_array['lat'],'lng'=>$destino_array['lng']]
                                    ];
                $newMerge['path_id']=$value['path_id'];
                $near_merge->push($newMerge);

            }
        }

        $route_response=new Collection;
        #Un solo camion me lleva al destino
        if($near_merge->count())
        {
            
            foreach ($near_merge as $merge) {
                $encode=$this->truncateRoute($merge,$latitude_origen, $longitude_origen, $latitude_destino, $longitude_destino);
                if(count($encode)>0){

                    $route=Route::where('id',$merge['route_id'])->first();
                    $route_array=[];
                    $route_array['id']=$route->id;
                    $route_array['name']=$route->name;
                    
                    $route_array['encodepath']=$encode;
                    $route_array['color']=$route->color;
                    $route_response->push($route_array);
                }
            }
            return $route_response;
        }

        #buscar transbordos
        $latitude_origin_tmp=($latitude_origen+$latitude_destino)/2;
        $longitude_origin_tmp=($longitude_origen+$longitude_destino)/2;
        $latitude_destiny_tmp=($latitude_origen+$latitude_destino)/2;;
        $longitude_destiny_tmp=($longitude_origen+$longitude_destino)/2;

        while ($near_merge->count()==0) {

            $latitude_origin_tmp=($latitude_origen+$latitude_origin_tmp)/2;
            $longitude_origin_tmp=($longitude_origen+$longitude_origin_tmp)/2;
            $latitude_destiny_tmp=($latitude_destino+$latitude_destiny_tmp)/2;
            $longitude_destiny_tmp=($longitude_destino+$longitude_destiny_tmp)/2;

            
            #Recorrer todas las rutas
            for ($index_rutasID=0; $index_rutasID < $routes_total; $index_rutasID++) { 
                $ruta_derroteros = Route::findOrFail($rutasID[$index_rutasID]->id)->paths;
                #Recorrer cada derrotero (Usualmente son dos por ruta)
                foreach ($ruta_derroteros as $derrotero) {
                    $derrotero_nodes = PolyUtil::decode($derrotero->encodepath);

                    #Si hay ruta cercana al nodo origen: array con lat,lng. Sino false
                    $temporal_origin=PolyUtil::isLocationOnPath_custom(
                        ['lat' => $latitude_origin_tmp, 'lng'=> $longitude_origin_tmp],
                        $derrotero_nodes,
                        $rango);
                    #Validar que haya ruta cercana al origen y que no se haya guardado dicha ruta
                    if($temporal_origin && !$near_origin->contains('path_id', $derrotero->id))
                    {
                        #Agregar id_ruta y el nodo de la ruta cercano al punto de origen
                        $near_node=[];
                        $near_node['route_id']=$rutasID[$index_rutasID]->id;
                        $near_node['path_id']=$derrotero->id;
                        $near_node['lat']=$temporal_origin['lat'];
                        $near_node['lng']=$temporal_origin['lng'];
                        $near_origin->push($near_node);
                        
                    }

                    #Si hay ruta cercana al nodo de destino: array con lat,lng. Sino false
                    $temporal_destiny=PolyUtil::isLocationOnPath_custom(
                        ['lat' => $latitude_destiny_tmp, 'lng'=> $longitude_destiny_tmp],
                        $derrotero_nodes,
                        $rango);
                    #Validar que exista ruta cercana al destino y no se haya guardado dicha rua
                    if($temporal_destiny && !$near_destiny->contains('path_id', $derrotero->id))
                    {
                        #Agregar id_ruta y el nodo de la ruta cercano al punto de destino
                        $near_node=[];
                        $near_node['route_id']=$rutasID[$index_rutasID]->id;
                        $near_node['path_id']=$derrotero->id;
                        $near_node['lat']=$temporal_destiny['lat'];
                        $near_node['lng']=$temporal_destiny['lng'];
                        $near_destiny->push($near_node);
                        
                    }
                }
            }

            #Encontrar rutas que se encuentren cerca tanto de origen como destino. Indica que un solo camión hace el recorrido
            foreach ($near_origin as $value) {
                if($near_destiny->contains('route_id', $value['route_id']) && $near_destiny->contains('path_id', $value['path_id']))
                {

                    $destino_tmp=$near_destiny->where('path_id',$value['path_id'])->all();
                    $destino_array=[];
                    foreach ($destino_tmp as $temp) {
                        $destino_array['route_id']=$temp['route_id'];
                        $destino_array['lat']=$temp['lat'];
                        $destino_array['lng']=$temp['lng'];
                    }
                    
                    $newMerge=[];
                    $newMerge['route_id']=$value['route_id'];
                    $newMerge['nodos']=['origen'=>['lat'=>$value['lat'], 'lng'=>$value['lng']],
                                        'destino'=>['lat'=>$destino_array['lat'],'lng'=>$destino_array['lng']]
                                        ];
                    $newMerge['path_id']=$value['path_id'];
                    $near_merge->push($newMerge);

                }
            }

            $route_response=new Collection;
            #Un solo camion me lleva al destino
            if($near_merge->count())
            {
                
                foreach ($near_merge as $merge) {
                    $encode=$this->truncateRoute($merge,$latitude_origen, $longitude_origen, $latitude_destino, $longitude_destino);
                    if(count($encode)>0){

                        $route=Route::where('id',$merge['route_id'])->first();
                        $route_array=[];
                        $route_array['id']=$route->id;
                        $route_array['name']=$route->name;
                        
                        $route_array['encodepath']=$encode;
                        $route_array['color']=$route->color;
                        $route_response->push($route_array);
                    }
                }
                return $route_response;
            }
        }
        
    }

    /**
     * Obtener segmento de rutas
     * @param array $ruta
    */
    public function truncateRoute($ruta,$latitude_origen, $longitude_origen, $latitude_destino, $longitude_destino)
    {
        $ruta_truncada=new Collection;
        $copiar=false;
        
        $paths=Path::where('id',$ruta['path_id'])->get();

        foreach ($paths as $path) {
            $path_nodes = PolyUtil::decode($path->encodepath);
            //$ruta_truncada->push(['lat'=>$latitude_origen,'lng'=>$longitude_origen]);
            foreach ($path_nodes as $node) {
                if($node['lat']==$ruta['nodos']['origen']['lat'] && $node['lng']==$ruta['nodos']['origen']['lng']){
                    $copiar=true;
                    
                }
                
                if($copiar){
                    $ruta_truncada->push($node);
                }
                if($node['lat']==$ruta['nodos']['destino']['lat'] && $node['lng']==$ruta['nodos']['destino']['lng'])
                {
                    $copiar=false;
                    break;
                }
            }
            //$ruta_truncada->push(['lat'=>$latitude_destino,'lng'=>$longitude_destino]);
            if($ruta_truncada->count()==0)
                $ruta_truncada=new Collection;
            
        }
        $encodepath=PolyUtil::encode($ruta_truncada->toArray());

        return $ruta_truncada->toArray();
    }
}