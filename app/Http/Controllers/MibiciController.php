<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MibiciController extends Controller
{
	public function index()
    {   
        return view('mibici.index');
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
}