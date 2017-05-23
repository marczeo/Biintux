<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\User;
use App\Driver;
use App\Rel_concessionaire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

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
     * Create user
     * @param Request $request
     * @return User $user
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
            $driver->route_car_id=$request->bus_id;
            $driver->concessionaire_id=$currentUser->id;
            $driver->save();
        }
        
        
        
        return $user;
    }

    /**
     * Update user
     * @param Request $request
     * @param User $user
     * @return bolean
     */
    public function updateUser(Request $request, User $user)
    {
        $currentUser=Auth::user();

        $user->name     = $request->name;
        $user->email    = $request->email;
        if($request->password != "" && $request->password_confirmation!="")
        {
            $user->password=bcrypt($request->password);
        }
        $user->save();
        if($currentUser->isAdmin())
        {

        }
        elseif ($currentUser->isConcessionaire()) {
            $driver=$user->driver;
            $driver->route_car_id=$request->bus_id;
            $driver->save();
        }
        
        return true;
    }

    /**
     * Eliminar usuario
     * @param User $user
     * @param boolean
    */
    public function deleteUser(User $user)
    {
        try {
            $user->delete();
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * Autenticar usuario desde API
     * @param Array $credentials
     * @return json
    */
    public function authenticate($credentials)
    {
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        //return response()->json(compact('token'));
        $user = Auth::user();
        $rol=$user->role->description;
        if($rol=="Driver"){
            $id_bus=$user->driver->route_car_id;
            return response()->json(compact('token','rol','id_bus'))->header('Content-Type','application/json');
        }
        return response()->json(compact('token','rol'))->header('Content-Type','application/json');
    }

    /**
     * Registrar usuario desde API
     * @param array $request
     * @return json
    */
    public function register($request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) 
        {
            return response()->json(['error' => 'An error occured, verify your data'], 401);
        }

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'role_id' => 1,
        ]);
        return $this->authenticate($request->only('email', 'password'));
    }
}