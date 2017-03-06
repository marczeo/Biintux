<?php

namespace App\Repositories;

use App\Ciclovia;
use Illuminate\Support\Collection;

class CicloviaRepository
{

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

     public function getAllCiclovias()
    {
        $ciclovias =Ciclovia::orderBy('id','asc')->get();
        
        
        $ciclovia_response=new Collection;
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
        }
        return json_encode($ciclovia_response);

        //return Ciclovia::orderBy('id','asc')->get();
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