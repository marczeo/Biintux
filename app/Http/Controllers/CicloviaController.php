<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CicloviaController extends Controller
{
	/**
     * Show the bikeway dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ciclovias.index');
    }
}
