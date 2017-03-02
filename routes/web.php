<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/    
Route::get('/', function () 
{
        return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');


/*
|--------------------------------------------------------------------------
| LanguageController
|--------------------------------------------------------------------------
*/
Route::get('/english','LanguageController@english');
Route::get('/spanish','LanguageController@spanish');

Route::group(['middleware' => 'auth'], function () 
{


    /*


    |--------------------------------------------------------------------------
     | miBici
    |--------------------------------------------------------------------------
    */

        Route::get('/getAll','MibiciController@getAll');
        Route::get('/mibici', 'MibiciController@index');
        Route::get('/mibici/create', 'MibiciController@create');
        Route::get('/mibici/destroy', 'MibiciController@destroy');
        Route::get('/mibici/edit', 'MibiciController@edit');
        Route::post('/deleteNode', 'MibiciController@deleteNode');
        Route::post('/updateNodes', 'MibiciController@updateNodes');
        Route::post('/mibici', 'MibiciController@post');



});

/*
|--------------------------------------------------------------------------
| Gestion de usuarios
|--------------------------------------------------------------------------
*/
Route::resource('user','UserController');
Route::get('/perfil', 'UserController@show');
Route::get('/getAllUser','UserController@getAllJson');


/*
|--------------------------------------------------------------------------
| Ciclovía
|--------------------------------------------------------------------------
*/
 Route::resource('ciclovia', 'CicloviaController');
 Route::get('/getAllCiclovia','CicloviaController@getAllJson');

 /*
|--------------------------------------------------------------------------
| Camiones
|--------------------------------------------------------------------------
*/
 Route::resource('bus', 'BusController');
  Route::get('/getAllBus','BusController@getAllJson');

  /*
|--------------------------------------------------------------------------
| Rutas de Camiones
|--------------------------------------------------------------------------
*/
 Route::resource('route', 'RouteController');
  Route::get('/getAllRoute','Routeontroller@getAllJson');