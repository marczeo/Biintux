<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UsuarioRepository;
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
    public function __construct(UsuarioRepository $users)
    {
        //$this->middleware('auth');

        $this->usersDAO = $users;
    }
	/**
     * Show the user dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $usuarios = $this->usersDAO->getAllusuarios()->get();
        return view('user.index',compact('usuarios'));
    }

    
    /**
     * Add a user to database
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect('/user');
    }

    /**
     * Show the form to new user
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Save changes user.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return view('user.update');
    }

    /**
     * Delete user from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return view('user.destroy');
    }

    /**
     * Show a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('user.destroy');
    }

    /**
     * Show the user form to edit.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('user.edit');
    }
}
