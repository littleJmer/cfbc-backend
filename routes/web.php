<?php
use Illuminate\Support\Facades\Auth;
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
  if(!Auth::check())
    return view('welcome');
  else
    return redirect('/almacen');
});

Auth::routes();

// WEB APP
Route::get('/almacen', 'HomeController@almacen')->name('almacen');
Route::get('/ordenes', 'HomeController@index')->name('ordenes');
Route::get('/recetas', 'HomeController@recetas')->name('recetas');

Route::post('app/importar/ordenes', 'HomeController@importar_ordenes');
Route::post('app/liberar/ordenes', 'HomeController@liberar_ordenes');
Route::post('app/master', 'HomeController@master');
Route::get('app/imprimir_master/orden/{id}', 'HomeController@imprimir_master_orden');
Route::get('app/imprimir/orden/{id}', 'HomeController@imprimir_orden');

// WEB API
Route::post('app/api/askForUser', 'HomeController@askForUser');

Route::get('app/api/inventario', 'HomeController@get_inventario');
Route::post('app/api/inventario/editar/{id}', 'HomeController@editar_inventario');

Route::get('app/api/ordenes', 'HomeController@get_ordenes');
Route::get('app/api/orden/{id}', 'HomeController@get_orden');

Route::get('app/api/recetas', 'HomeController@get_recetas');
Route::get('app/api/recetas/{id}', 'HomeController@get_recetas_by_id');
Route::post('app/api/recetas/{id}', 'HomeController@recetas_update');

