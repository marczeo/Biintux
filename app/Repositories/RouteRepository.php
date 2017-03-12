<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Route;
use App\Rel_route;
use App\Node;
use Illuminate\Support\Collection;
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
     * @return json
     */
    public function getAllRoutes()
    {
        /*$routes= Route::orderBy('id','asc')->select('id','code', 'direction','first_run','last_run','encodepath','color')->get();
        $route_response=[];
        foreach ($routes as $key=> $route)
        {

            $route_response[$key]['id']=$route->id;
            $route_response[$key]['code']=$route->code;
            $route_response[$key]['direction']=$route->direction;
            $route_response[$key]['first_run']=$route->first_run;
            $route_response[$key]['last_run']=$route->last_run;
            $route_response[$key]['encodepath']=$route->encodepath;
            $route_response[$key]['color']=$route->color;
        }
        return json_encode($route_response);*/

        //
        $routes =Route::orderBy('id','asc')->get();
        
        
        $route_response=new Collection;
        foreach ($routes as $key=> $route)
        {
            $routeA=[];
            $nodos = new Collection;
            $rel_routes=$route->rel_route;
            foreach ($rel_routes as $key => $rel_route) {
                $nodo=[];
                $nodo['longitude']=$rel_route->start_node->longitude;
                $nodo['latitude']=$rel_route->start_node->latitude;
                $nodos->push($nodo);
            }
            $routeA['id']=$route->id;
            $routeA['code']=$route->code;
            //$routeA['name']=$route->name;
            $routeA['encodepath']=$route->encodepath;
            $routeA['color']=$route->color;
            $routeA['nodos']=$nodos;
            $route_response->push($routeA);
        }
        return json_encode($route_response);
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

        //$route->code="BW-666";
        $route->code=$request->name;
        $route->encodepath=$request->encodePath;
        $route->direction=1;
        $route->first_run=$request->first_run;
        $route->last_run=$request->last_run;
        

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
}