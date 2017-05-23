<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Ciclovia;
use App\Rel_cycling;
use App\Node;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CicloviaRepository
{
    /**
     * The type node associated with the controller.
     *
     * @var string
     */

    protected $typeNode = 'bikeway';

    /**
     * Get all of the bikeways
     *
     * @param  null
     * @return Collection
     */
    public function getAllBikewaysID()
    {
        $ids =Ciclovia::orderBy('id','asc')->select('id')->get();
        return $ids;
    }

    /**
     * Get all of the routes  for a given user.
     *
     * @param  User  $user
     * @return Collection
     */
    /*public function forUser(User $user)
    {
        return $user->routes()
                    ->orderBy('created_at', 'asc')
                    ->get();
    }*/

    /**
     * Get all of the bikeways
     *
     * @param  null
     * @return json
     */
     public function getAllCiclovias()
    {
        $ciclovias =Ciclovia::orderBy('id','asc')->get();
        
        
        $ciclovia_response=new Collection;
        foreach ($ciclovias as $key=> $ciclovia)
        {
            $ciclovia_array=[];
            $nodos = new Collection;
            $rel_cyclings=$ciclovia->rel_cycling;
            foreach ($rel_cyclings as $key => $rel_cycling) {
                $nodo=[];
                $nodo['longitude']=$rel_cycling->start_node->longitude;
                $nodo['latitude']=$rel_cycling->start_node->latitude;
                $nodos->push($nodo);
            }
            $ciclovia_array['id']=$ciclovia->id;
            $ciclovia_array['code']=$ciclovia->code;
            $ciclovia_array['name']=$ciclovia->name;
            $ciclovia_array['encodepath']=$ciclovia->encodepath;
            $ciclovia_array['color']=$ciclovia->color;
            $ciclovia_array['nodos']=$nodos;
            $ciclovia_response->push($ciclovia_array);
        }
        $response['data']=$ciclovia_response;
        return json_encode($response);
    }

    /**
     * Create a new ciclovia
     *
     * @param  Request $request
     * @return Boolean
     */
    public function createCiclovia(Request $request)
    {
        $ciclovia = new ciclovia;
        $ciclovia->setColor();
        while ($this->existColor($ciclovia->color)){
            $ciclovia->setColor();
        }

        $ciclovia->code="BW-666";
        $ciclovia->name=$request->name;
        $ciclovia->encodepath=$request->encodePath;
        $ciclovia->save();

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
            }
            else{
                $rel_cycling->next_node_id=$newNode->id;
                $rel_cycling->save();
            }
            $rel_cycling = new Rel_cycling;
            $rel_cycling->cycling_route_id = $ciclovia->id;
            $rel_cycling->start_node_id=$newNode->id;

        }
        $rel_cycling->save();
        return true;
    }

    /**
     * Delete a bikeway
     *
     * @param  int $IDciclovia
     * @return boolean
     */
    public function deleteCiclovia($IDciclovia)
    {
        try{
            $ciclovia=Ciclovia::findOrFail($IDciclovia);
            foreach ($ciclovia->rel_cycling as $key => $rel_cycling) {
                $rel_cycling->delete();
            }
            $ciclovia->delete();
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
        
        if(Ciclovia::where('color', $color)->count())
            return true;
        return false;

    }

    /**
     * Obtener ciclovias cercanas a partir
     * https://github.com/alexpechkarev/geometry-library#isLocationOnEdge
     * @param double latitude
     * @param double longitude
     * @param decimal rango
     * @return Collection ciclovias
     */
    public function nearBikeways($latitude, $longitude, $rango)
    {
        $bikewaysID = $this->getAllBikewaysID();
        $ciclovias_total= $bikewaysID->count();
        $nearID_array = array();
        for ($index_bikewaysID=0; $index_bikewaysID < $ciclovias_total; $index_bikewaysID++) { 
            $ciclovia = Ciclovia::findOrFail($bikewaysID[$index_bikewaysID]->id);

                $poly_nodes =  \GeometryLibrary\PolyUtil::decode($ciclovia->encodepath);
                if(\GeometryLibrary\PolyUtil::isLocationOnEdge(
                    ['lat' => $latitude, 'lng'=> $longitude],
                    $poly_nodes,
                    $rango))
                {
                    array_push($nearID_array,$bikewaysID[$index_bikewaysID]->id);
                }
            

        }


        $ciclovias= Ciclovia::whereIn('id', $nearID_array)
                    ->get();
        $ciclovia_response=new Collection;
        foreach ($ciclovias as $key=> $ciclovia)
        {
            $route_array=[];
            $route_array['id']=$ciclovia->id;
            $route_array['name']=$ciclovia->name;
            $route_array['encodepath']=$ciclovia->encodepath;
            $route_array['color']=$ciclovia->color;
            $ciclovia_response->push($route_array);
        }
        //$response['data']=$ciclovia_response;
        
        return $ciclovia_response;
    }
}