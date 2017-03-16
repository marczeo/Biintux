<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use Session;
use App\Repositories\UsuarioRepository;
use App\Repositories\RoleRepository;
use App\Repositories\RouteRepository;
use App\Repositories\BusRepository;
use Illuminate\Support\Facades\Auth;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * The usuario repository instance.
     *
     * @var usuarioRepository
     */
    protected $usersDAO;

    /**
     * Create a new controller instance.
     *
     * @param  userRepository  $users
     * @return void
     */
    public function __construct(UsuarioRepository $users, Request $request)
    {
        //Cuando la petición es desde API
        if($request->route()){
            if($request->route()->getPrefix()=="api"){
                $this->middleware('jwt.auth',['only'=>['getAllJson']]);
            }
            else{#Peticion desde web
                $this->middleware('auth');
                $this->middleware('concessionaire',['only' => [
                    'index',
                    'destroy',
                ]]);

            }
        }


        //Use DAO
        $this->usersDAO = $users;
    }
	/**
     * Show the user dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('user.index');
    }

    
    /**
     * Add a user to database
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newUser=$this->usersDAO->createUser($request);

        //Enviar por correo
        //Mail::to($newUser)->send(new UserCreated($newUser));
        return redirect('/user');
    }

    /**
     * Show the form to new user
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser=Auth::user();
        $roleDAO = new RoleRepository();
        $roles = json_decode($roleDAO->getAllRoles());

        $routeDAO = new RouteRepository();
        $rutas = json_decode($routeDAO->getAllRoutes(null))->data;

        if(!$currentUser->isAdmin() && $currentUser->isConcessionaire()){
            $busDAO = new BusRepository();
            $buses=json_decode($busDAO->getAllBuses());
            return view('user.create',compact('roles', 'rutas','buses'));
        }
        return view('user.create',compact('roles', 'rutas'));
    }

    /**
     * Save changes user.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->usersDAO->updateUser($request,$user);
        return redirect('/user');
    }

    /**
     * Delete user from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$user=User::findOrFail($id);
    	$user->delete();
        return redirect('/user');
    }

    /**
     * Show a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user = null)
    {
        if($user->id==null)
            $user=Auth::user();
        return view('user.show', compact('user'));
    }

    /**
     * Show the user form to edit.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $currentUser=Auth::user();
        if($currentUser->id==$user->id)#Usuario actual editará sus datos
        {
            return view('user.edit', compact('user'));
        }
        elseif($currentUser->isAdmin()){
            return view('user.edit', compact('user'));
        }
        elseif($currentUser->isConcessionaire() && $currentUser->id==$user->driver->concessionaire_id)#Usuario actual es concesionario y se editará un conductor
        {
            $busDAO = new BusRepository();
            $buses=json_decode($busDAO->getAllBusesForUser($currentUser));
            return view('user.edit', compact('user','buses'));
        }
        flash('No tiene permiso', 'danger');
        return redirect('/perfil');
    }

    /**
    * Show all bikeways
    * @return json
    */
    public function getAllJson()
    { 
        $usuarios = $this->usersDAO->getAllUsuarios();
        return $usuarios;
    }

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

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
        return response()->json(compact('token','rol'))->header('Content-Type','application/json');
    }
}
