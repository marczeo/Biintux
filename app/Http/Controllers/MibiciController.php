<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mibici;
use DB;

class MibiciController extends Controller
{
	public function index()
    {   
        $mibicis = Mibici::orderBy('id','asc')->get(); 
        //dd($mibicis);   
        return view('mibici.index',compact('mibicis'));
    }

    public function getAll()
    { 
        $mibicis = Mibici::orderBy('id','asc')->select('encodepath')->get();  
        //dd($mibicis);  
        return $mibicis;
    }
    
    public function create()
    {
        return view('mibici.create');
    }

    public function edit()
    {
        return view('mibici.edit');
    }

    public function destroy()
    {
        return view('mibici.destroy');
    }

    public function show()
    {
    }

    public function store(Request $request)
    {
        $mibici = new Mibici;
        $mibici->latitude = $request->markerFromLat; 
        $mibici->longitude = $request->markerFromLang;
        $mibici->description = $request->markerFromAddress;
        $mibici->type = 1;//???????
        $mibici->encodepath=$request->encodePath;
        $mibici->save();
        //dd($mibici);
        return redirect('/mibici');
    }
}