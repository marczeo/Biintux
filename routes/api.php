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
Route::get('/register','UserController@register');

/*
|--------------------------------------------------------------------------
| Rutas de camiones
|--------------------------------------------------------------------------
*/
Route::get('/getAllRoute','RouteController@getAllJson');
Route::get('/getAllRoute/{type}','RouteController@getAllJson');
Route::get('/getNearRoutes','RouteController@getNearRoutes');
Route::get('/route/{route}/getNodes','RouteController@getNodes');

 /*
|--------------------------------------------------------------------------
| Camiones
|--------------------------------------------------------------------------
*/
 Route::get('/getAllBus','BusController@getAllJson');
 Route::get('/bus/{bus}/enabled','BusController@changeStatus');
 /*
|--------------------------------------------------------------------------
| Localización
|--------------------------------------------------------------------------
*/
Route::get('/device_location','Device_locationController@store');
Route::get('/getAllLocation','Device_locationController@getAllJson');

 /*
|--------------------------------------------------------------------------
| Devices
|--------------------------------------------------------------------------
*/

Route::get('/register_device','DeviceController@store');
Route::get('/device/validate','DeviceController@validate_device');
Route::get('/device/assignUser','DeviceController@assignUserDevice');