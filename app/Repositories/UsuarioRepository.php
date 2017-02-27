<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
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
        $usuarios= User::orderBy('id','asc')->select('id','name','email','role_id')->get();
        $user_response=[];
        foreach ($usuarios as $key=> $user)
        {

            $user_response[$key]['id']=$user->id;
            $user_response[$key]['name']=$user->name;
            $user_response[$key]['email']=$user->email;
            $user_response[$key]['role']=$user->role->description;
            $user_response[$key]['color']=$user->getColor();
        }
        return json_encode($user_response);
    }

    /**
     *Create user
     */
    public function createUser(Request $request)
    {
        $user=new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password=bcrypt('secret');
        if(Auth::user()->isAdmin())
        {
            $user->role_id=$request->role_id;
        }
        else{
            $user->role_id=2;
        }
        
        
        $user->save();
        return true;
    }

    /**
     *Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $user->name     = $request->name;
        $user->email    = $request->email;
        if($request->password != "" && $request->password_confirmation!="")
        {
            $user->password=bcrypt($request->password);
        }
        $user->save();
        return true;
    }
}