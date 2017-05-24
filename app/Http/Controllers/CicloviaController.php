<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Session;
use App\Ciclovia;
use App\Rel_cycling;
use App\Node;
use App\Repositories\CicloviaRepository;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CicloviaController extends Controller
{
    /**
     * The type node associated with the controller.
     *
     * @var string
     */

    protected $typeNode = 'bikeway';

     /**
     * The ciclovia repository instance.
     *
     * @var CicloviaRepository
     */
    protected $cicloviasDAO;

    /**
     * Create a new controller instance.
     *
     * @param  CicloviaRepository  $ciclovias
     * @return void
     */
    public function __construct(CicloviaRepository $ciclovias, Request $request)
    {
        //Cuando la petición es desde API
        if($request->route()){
        if($request->route()->getPrefix()=="api"){
            $this->middleware('jwt.auth',['except'=>['getAllJson']]);
        }
        else{#Peticion desde web
            $this->middleware('auth');
            $this->middleware('admin',['except' => [
                'show',
                'getAllJson'
            ]]);
        }}

        $this->cicloviasDAO = $ciclovias;
    }
	/**
     * Show the bikeway dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('ciclovia.index');
    }

    /**
     * Add a bikeway to database
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	if($this->cicloviasDAO->createCiclovia($request))
            flash('Ciclovia creada con exito','success');
        else
            flash('Error al intentar crear ciclovia','danger');
        return redirect('/ciclovia');
    }

    /**
     * Show the form to new bikeway
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ciclovia.create');
    }

    /**
     * Save changes bikeway.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return view('ciclovia.update');
    }

    /**
     * Delete bikeway from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->cicloviasDAO->deleteCiclovia($id))
            flash('Ciclovía eliminada con exito', 'success');
        else
            flash('Error al intentar eliminar ciclovia','danger');
        return redirect('/ciclovia');
    }

    /**
     * Show a bikeway.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('ciclovia.destroy');
    }

    /**
     * Show the bikeway form to edit.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('ciclovia.edit');
    }
    
    /**
    * Show all bikeways
    * @return json
    */
    public function getAllJson()
    { 
        $ciclovias = $this->cicloviasDAO->getAllCiclovias();
        return response()->json($ciclovias);
    }
}
