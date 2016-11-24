<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ciclovia;
use App\Ciclovia_nodo;

class CicloviaController extends Controller
{
	/**
     * Show the bikeway dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $ciclovias = Ciclovia::all();    
        return view('ciclovia.index',compact('ciclovias'));
    }

    /**
     * Add a bikeway to database
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$ciclovia = new ciclovia;
        $ciclovia_nodo_start = new Ciclovia_nodo;
        $ciclovia_nodo_end = new Ciclovia_nodo;

        $ciclovia_nodo_start->longitude = $request->markerFromLang;
        $ciclovia_nodo_start->latitude = $request->markerFromLat;
        $ciclovia_nodo_start->save();

        $ciclovia_nodo_end->longitude = $request->markerToLang;
        $ciclovia_nodo_end->latitude = $request->markerToLat;
        $ciclovia_nodo_end->save();
        $ciclovia_nodo_start->next_nodo=$ciclovia_nodo_end->id;
        $ciclovia_nodo_start->save();

        $ciclovia->name=$request->name;
        $ciclovia->start_nodo=$ciclovia_nodo_start->id;
        $ciclovia->end_nodo=$ciclovia_nodo_end->id;
        $ciclovia->save();

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
    public function destroy()
    {
        return view('ciclovia.destroy');
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
}
