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
        //$ciclovia_response=[];
        foreach ($ciclovias as $key=> $ciclovia)
        {
            $cicloviaA=[];
            $nodos = new Collection;
            $rel_cyclings=$ciclovia->rel_cycling;
            foreach ($rel_cyclings as $key => $rel_cycling) {
                $nodo=[];
                $nodo['longitude']=$rel_cycling->start_node->longitude;
                $nodo['latitude']=$rel_cycling->start_node->latitude;
                $nodos->push($nodo);
            }
            $cicloviaA['id']=$ciclovia->id;
            $cicloviaA['code']=$ciclovia->code;
            $cicloviaA['name']=$ciclovia->name;
            $cicloviaA['encodepath']=$ciclovia->encodepath;
            $cicloviaA['color']=$ciclovia->color;
            $cicloviaA['nodos']=$nodos;
            $ciclovia_response->push($cicloviaA);
            //$ciclovia_response[$key]=$cicloviaA;
        }
        $test['data']=$ciclovia_response;
        return json_encode($test);
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
}