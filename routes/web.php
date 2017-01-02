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
Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index');
/*
|--------------------------------------------------------------------------
| Language
|--------------------------------------------------------------------------
*/
Route::get('/english','LanguageController@english');
Route::get('/spanish','LanguageController@spanish');
/*
|--------------------------------------------------------------------------
| Ciclovía
|--------------------------------------------------------------------------
*/
//Route::get('/ciclovias', 'CicloviaController@index');
Route::get('/getAll','CicloviaController@getAll');
Route::resource('ciclovia', 'CicloviaController');
/*
|--------------------------------------------------------------------------
| miBici
|--------------------------------------------------------------------------
*/

Route::group(['/mibici' => 'auth'], function () 
{
    Route::get('/mibici', function () 
    {
        return view('/mibici/index');
    });
    Route::get('/mibici/create', function () 
    {
        return view('/mibici/create');
    });
    Route::get('/mibici/edit', function () 
    {
        return view('/mibici/edit');
    });
    Route::get('/mibici/destroy', function () 
    {
        return view('/mibici/destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Perfil
|--------------------------------------------------------------------------
*/
Route::get('perfil', function() 
{
    return view('profile');
})->middleware('auth');