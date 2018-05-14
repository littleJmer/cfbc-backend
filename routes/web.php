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
			return redirect('/ordenesv2');
	});

Auth::routes();

/*
      __            /^\
    .'  \          / :.\   
   /     \         | :: \ 
  /   /.  \       / ::: | 
 |    |::. \     / :::'/  
 |   / \::. |   / :::'/
 `--`   \'  `~~~ ':'/`
         /         (    
        /   0 _ 0   \   
      \/     \_/     \/  
    -== '.'   |   '.' ==-   
      /\    '-^-'    /\    
        \   _   _   /             
       .-`-((\o/))-`-.   
  _   /     //^\\     \   _    
."o".(    , .:::. ,    )."o".  
|o  o\\    \:::::/    //o  o| 
 \    \\   |:::::|   //    /   
  \    \\__/:::::\__//    /   
   \ .:.\  `':::'`  /.:. /      
    \':: |_       _| ::'/  
 jgs `---` `"""""` `---`


Version 2
*/
Route::get('/ordenesv2', 'OrdenController@index');
Route::get('/ordenesv2/imprimir/{id}', 'OrdenController@imprimir');
Route::post('/ordenesv2/importar', 'OrdenController@importar');

Route::get('/inventariov2', 'InventarioController@index');

// Matches The "/app/apiv2" URL
Route::prefix('app/apiv2')->group(function () {

    Route::get('ordenes', 'OrdenController@get');
    Route::post('ordenes/explosion', 'OrdenController@makeExcel');
    Route::post('ordenes/recetas/toggle', 'OrdenController@recipesOpenOrClose');
    Route::post('ordenes/recetas/swap/{id}', 'OrdenController@recipesSwap');

    Route::get('inventario', 'InventarioController@get');
    Route::post('inventario', 'InventarioController@store');
    Route::post('inventario/{id}', 'InventarioController@update');

    Route::post('master', 'InventarioController@save_master');

    Route::get('planes', 'PlanController@get');
    Route::get('planes/{id}', 'PlanController@get_by_id');

    Route::get('inventario/flower_types', 'InventarioController@get_flower_types');
    Route::get('inventario/variety_colors', 'InventarioController@get_variety_colors');
    Route::get('inventario/para_planificar', 'InventarioController@get_para_planificar');

});

// flower type
Route::get('/config/flower-type', 'ConfigController@show_flowerType');
Route::get('/app/api/flower-type', 'ConfigController@get_flowerType');
Route::post('/app/api/flower-type', 'ConfigController@store_flowerType');
Route::post('/app/api/flower-type/{id}', 'ConfigController@update_flowerType');

// color code
Route::get('/config/color-codes', 'ConfigController@show_colorCodes');
Route::get('/app/api/color-codes', 'ConfigController@get_colorCodes');
Route::post('/app/api/color-codes', 'ConfigController@store_colorCodes');
Route::post('/app/api/color-codes/{id}', 'ConfigController@update_colorCodes');

// grade key
Route::get('/config/grade-key', 'ConfigController@show_gradeKey');
Route::get('/app/api/grade-key', 'ConfigController@get_gradeKey');
Route::post('/app/api/grade-key', 'ConfigController@store_gradeKey');
Route::post('/app/api/grade-key/{id}', 'ConfigController@update_gradeKey');















// WEB APP
Route::get('/ordenes', 'HomeController@index')->name('ordenes');
Route::get('/almacen', 'HomeController@almacen')->name('almacen');
Route::get('/planes', 'HomeController@planes')->name('planes');
Route::get('/flores', 'HomeController@flores')->name('flores');
Route::get('/recetas', 'HomeController@recetas')->name('recetas');
Route::get('/clientes', 'HomeController@clientes')->name('clientes');
Route::get('/materiales', 'HomeController@materiales')->name('materiales');

Route::post('app/importar/ordenes', 'HomeController@importar_ordenes');
Route::post('app/liberar/ordenes', 'HomeController@liberar_ordenes');
Route::post('app/activar/ordenes', 'HomeController@activar_ordenes');

Route::post('app/master', 'HomeController@master');
Route::post('app/planner/init', 'HomeController@planner_init');
Route::post('app/planner/dd', 'HomeController@planner_dd');
Route::post('app/planner/save', 'HomeController@planner_save');
Route::get('app/planner/{id}/shipping', 'HomeController@planner_shipping');

Route::get('app/checkRecipes', 'HomeController@checkRecipes');
Route::get('app/imprimir_master/orden/{id}', 'HomeController@imprimir_master_orden');
Route::get('app/imprimir/orden/{id}', 'HomeController@imprimir_orden');

// WEB API
Route::post('app/api/askForUser', 'HomeController@askForUser');

Route::get('app/api/planes', 'HomeController@planners');
Route::get('app/api/planes/{id}', 'HomeController@planners_by_id');

Route::get('app/api/inventario', 'HomeController@get_inventario');
Route::post('app/api/inventario/editar/{id}', 'HomeController@editar_inventario');

Route::get('app/api/ordenes', 'HomeController@get_ordenes');
Route::get('app/api/orden/{id}', 'HomeController@get_orden');
Route::post('app/api/resetOrder/{id}', 'HomeController@reset_orden');

Route::get('app/api/flores', 'HomeController@get_flores');
Route::post('app/api/flores', 'HomeController@UpdateOrCreate_flores');
Route::get('app/api/flores/{id}', 'HomeController@get_flores_by_id');

Route::get('app/api/recetas', 'HomeController@get_recetas');
Route::get('app/api/recetas/{id}', 'HomeController@get_recetas_by_id');
Route::post('app/api/recetas/{id}', 'HomeController@recetas_update');

Route::get('app/api/customers', 'HomeController@get_customers');
Route::post('app/api/customers', 'HomeController@UpdateOrCreate_customers');
Route::get('app/api/customers/{id}', 'HomeController@get_customers_by_id');

Route::get('app/api/items', 'HomeController@get_items');
Route::post('app/api/items', 'HomeController@UpdateOrCreate_items');
Route::get('app/api/items/{id}', 'HomeController@get_items_by_id');

Route::get('app/api/catalogos_recetas', 'HomeController@get_catalogos_recetas');


