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
| Mibici
|--------------------------------------------------------------------------
*/
Route::get('/getAllMibici','MibiciController@getAllJson');

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
Route::get('/getAllRoute/{type}','RouteController@getAllJson');

 /*
|--------------------------------------------------------------------------
| Camiones
|--------------------------------------------------------------------------
*/
 Route::get('/getAllBus','BusController@getAllJson');

 /*
|--------------------------------------------------------------------------
| Localización
|--------------------------------------------------------------------------
*/
Route::get('/device_location','Device_locationController@store');
Route::get('/getAllLocation','Device_locationController@getAllJson');