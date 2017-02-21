<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Node;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use DB;

class MibiciController extends Controller
{
	public function index()
    {
        return view('mibici.index');
    }

    public function getAll()
    { 
        $mibicis = Node::Where('type','mibici')->get(); 
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

    public function updateNodes(Request $request)
    {
        DB::table('nodes')
            ->where('id', '=', $request->id)
            ->update(
                array( 
                    "latitude" => $request->markerFromLat,
                    "longitude" => $request->markerFromLang,
                    "description" => $request->name,
                )
            );

        return redirect('/mibici');
    }

    public function deleteNode(Request $request)
    { 
        DB::table('nodes')->where('id', '=', $request->id)->delete();

        return redirect('/mibici');
    }

    public function post(Request $request)
    {
        $mibici = new Node;
        $mibici->latitude = $request->markerFromLat; 
        $mibici->longitude = $request->markerFromLang;
        $mibici->description = $request->name;
        $mibici->type = "mibici";
        $mibici->save();

        return redirect('/mibici');
    }
}