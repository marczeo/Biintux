<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Device_locationRepository;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\Facades\Auth;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class Device_locationController extends Controller
{
    /**
     * The usuario repository instance.
     *
     * @var usuarioRepository
     */
    protected $DeviceLocationDAO;

    /**
     * Create a new controller instance.
     *
     * @param  Device_locationRepositiry  $device
     * @return void
     */
    public function __construct(Device_locationRepository $device, Request $request)
    {
        //Cuando la petición es desde API
        if($request->route()){
        if($request->route()->getPrefix()=="api"){
            $this->middleware('jwt.auth',['only'=>['getAllJson']]);
        }
        else{#Peticion desde web
            $this->middleware('auth');
            

        }}
        //Use DAO
        $this->DeviceLocationDAO = $device;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->DeviceLocationDAO->addLocation($request);
        return json_encode($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
    * Show all Location
    * @return json
    */
    public function getAllJson()
    { 
        $locations = $this->DeviceLocationDAO->getAllDevice_location();
        return $locations;
    }
}
