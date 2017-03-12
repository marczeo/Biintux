<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bus;
use App\Rel_concessionaire;
use App\Route;
use App\Node;
use App\Repositories\RouteRepository;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class RouteController extends Controller
{
     /**
     * The route repository instance.
     *
     * @var rutasRepository
     */
    protected $rutasDAO;

    /**
     * Create a new controller instance.
     *
     * @param  CicloviaRepository  $ciclovias
     * @return void
     */
    public function __construct(RouteRepository $rutas, Request $request)
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
            }
        }
        $this->rutasDAO = $rutas;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('routes.index');
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
        //
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

    public function getBuses(Request $request, $id)
    {
        if($request->ajax()){
            $buses=Bus::where('concessionaire_id',$id)->get();
            return response()->json($buses);
        }
    }
    public function getConcesionarios(Request $request, $id)
    {
        if($request->ajax()){
            $buses=Rel_concessionaire::where('route_id',$id)->get();
            $user_response=[];
            foreach ($buses as $key=> $user)
            {

                $user_response[$key]['id']=$user->user->id;
                $user_response[$key]['name']=$user->user->name;
                $user_response[$key]['email']=$user->user->email;
                $user_response[$key]['role']=$user->user->role->description;
                $user_response[$key]['color']=$user->user->getColor();
            }
            return response()->json($user_response);
        }
    }
    /**
    * Show all bikeways
    * @return json
    */
    public function getAllJson()
    { 
        $ciclovias = $this->rutasDAO->getAllRoutes();
        return $ciclovias;
    }
}
