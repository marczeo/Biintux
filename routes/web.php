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

Route::get('/getAll','MibiciController@getAll');
Route::get('/mibici', 'MibiciController@index');
Route::get('/mibici/create', 'MibiciController@create');
Route::get('/mibici/destroy', 'MibiciController@destroy');
Route::get('/mibici/edit', 'MibiciController@edit');
Route::post('/mibici/store', 'MibiciController@store');

/*
|--------------------------------------------------------------------------
| Perfil
|--------------------------------------------------------------------------
*/
Route::get('perfil', function() 
{
    return view('profile');
})->middleware('auth');