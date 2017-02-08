<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\User;

class UsuarioRepository
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
    public function getAllUsuarios()
    {
        return User::orderBy('id','asc');
    }
}