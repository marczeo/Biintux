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
        $rutas = json_decode(json_encode($routeDAO->getAllRoutes(null)))->data;

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
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
    	if($this->usersDAO->deleteUser($user))
            flash('Usuario eliminado con exito', 'success');
        else
            flash('Ocurrio un error, vuelta a intentar. Es posible que tenga alguna relación con otros usuarios', 'danger');
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

    /**
     * Login desde la API
     * @param Request $request
     * @return json
    */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        return $this->usersDAO->authenticate($credentials);
    }

    /**
     * Registro desde la API
    */
    public function register(Request $request)
    {
        return $this->usersDAO->register($request);
    }
}
