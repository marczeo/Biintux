<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Ciclovia;
use App\Rel_cycling;
use App\Node;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MibiciRepository
{
    /**
     * The type node associated with the controller.
     *
     * @var string
     */

    protected $typeNode = 'mibici';


    /**
     * Get all of the mibici stations
     *
     * @param  null
     * @return json
     */
     public function getAllMibici()
    {
        $estaciones =Node::where('type',$this->typeNode)->orderBy('id','asc')->get();
        
        
        $estaciones_response=new Collection;
        foreach ($estaciones as $key=> $estacion)
        {
            $estacion_array=[];
            $estacion_array['id']=$estacion->id;
            $estacion_array['description']=$estacion->description;
            $estacion_array['longitude']=$estacion->longitude;
            $estacion_array['latitude']=$estacion->latitude;
            $estaciones_response->push($estacion_array);
        }
        $response['data']=$estaciones_response;
        return json_encode($response);
    }
}