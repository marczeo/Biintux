<?php

namespace App\Repositories;

use App\Ciclovia;

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
        return Ciclovia::orderBy('id','asc')->get();
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