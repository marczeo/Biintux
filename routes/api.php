<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

/*
|--------------------------------------------------------------------------
| Ciclovía
|--------------------------------------------------------------------------
*/
Route::get('/getAllCiclovia','CicloviaController@getAllJson');

/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/
Route::get('/getAllUser','UserController@getAllJson');
Route::get('/login','UserController@authenticate');

/*
|--------------------------------------------------------------------------
| Rutas de camiones
|--------------------------------------------------------------------------
*/
Route::get('/getAllRoute','RouteController@getAllJson');

 /*
|--------------------------------------------------------------------------
| Camiones
|--------------------------------------------------------------------------
*/
 Route::get('/getAllBus','BusController@getAllJson');
