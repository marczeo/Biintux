<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class LanguageController extends Controller
{

	/**
     * Change the app's language to English.
     *
     * @return language
     */
    public function english()
    {
    	session()->put('lang','en');
    	return back();
    }

    /**
     * Change the app's language to Spanish.
     *
     * @return languaje
     */
    public function spanish()
    {
    	session()->put('lang','es');
    	return back();
    }
}
