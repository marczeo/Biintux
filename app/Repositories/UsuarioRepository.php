<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\User;
use App\Driver;
use App\Rel_concessionaire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
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
        $currentUser=Auth::user();
        $usuarios= new Collection;
        if($currentUser->isAdmin())
            $usuarios= User::orderBy('id','asc')->select('id','name','email','role_id')->get();
        elseif ($currentUser->isConcessionaire()) {
            foreach ($currentUser->drivers as $driver) {
                $usuarios->push($driver->user);
            }
        }
        $user_response=[];
        foreach ($usuarios as $key=> $user)
        {

            $user_response[$key]['id']=$user->id;
            $user_response[$key]['name']=$user->name;
            $user_response[$key]['email']=$user->email;
            $user_response[$key]['role']=trans('user.'.$user->role->description);
            $user_response[$key]['color']=$user->getColor();
        }
        return json_encode($user_response);
    }
    public function getAllConcessionaire()
    {
        $usuarios= User::where('role_id',3)->orderBy('id','asc')->select('id','name','email','role_id')->get();
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
        $currentUser=Auth::user();
        $user=new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password=bcrypt('secret');
        if($currentUser->isAdmin())
        {
            $user->role_id=$request->role_id;
            $user->save();
            if(!$user->isAdmin() && $user->isConcessionaire()){
                $rel_concessionaire = new Rel_concessionaire;
                $rel_concessionaire->concessionaire_id=$user->id;
                $rel_concessionaire->route_id=$request->route_id;
                $rel_concessionaire->save();
            }
            elseif ($user->isDriver()) {
                $driver=new Driver();
                $driver->user_id=$user->id;
                $driver->route_car_id=$request->bus_id;
                $driver->concessionaire_id= $request->concessionaire_id;
                $driver->save();
            }
        }
        elseif($currentUser->isConcessionaire())
        {
            $user->role_id=2;
            $user->save();
            $driver=new Driver();
            $driver->user_id=$user->id;
            $driver->route_car_id=$currentUser->rel_concessionaire()->route_id;
            $driver->concessionaire_id=$currentUser->id;
        }
        
        
        
        return $user;
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