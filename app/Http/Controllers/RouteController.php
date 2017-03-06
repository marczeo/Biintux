<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bus;
use App\Rel_concessionaire;
class RouteController extends Controller
{
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
}
