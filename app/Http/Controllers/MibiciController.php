<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MibiciController extends Controller
{
	public function index()
    {
        return view('mibici.index');
    }
}