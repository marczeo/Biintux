<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Route;

class RouteRepository
{

    public function createRoute()
    {
    }

    /**
     * Get all of the bus routes
     *
     * @param  null
     * @return json
     */
    public function getAllRoutes()
    {
        $routes= Route::orderBy('id','asc')->select('id','code', 'direction','first_run','last_run','encodepath','color')->get();
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
        return json_encode($route_response);
    }
}