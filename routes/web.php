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
    return redirect('lend');
});
Route::resources(['hardwaretype' => 'HwtypeController']);
Route::resources(['hardware'=>'HardwareController']);
Route::resources(['lend'=>'LendController']);
Route::get('lend/add/{values}','LendController@add_hw',function(){});
Route::get('lend/del/{values}','LendController@del_hw',function(){});
Route::get('lend/result/{values}','LendController@result',function(){});
Route::get('lendshow','LendController@lendshow');
Route::resources(['lendshow'=>'LendshowController']);
Route::get('lendshow/out/{values}','LendshowController@out');
Route::get('lendshow/back/{values}','LendshowController@back');
Route::get('lendshow/add/{values}','LendshowController@add_hw');
Route::get('lendshow/del/{values}','LendshowController@del_hw');
//Route::get('search','HardwareController@search');
Route::resources(['search'=>'SearchController']);
Route::get('search/hardware/{values}','SearchController@hardware');
Route::get('hardware/search/{values}','HardwareController@history');
Route::get('hardware/0/{values}','HardwareController@status');
Route::get('hardware/delete/{values}','HardwareController@harddel');
Route::get('hardwaretype/delete/{values}','HwtypeController@harddel');
Route::get('events', 'EventController@index');
Route::get('events/{id}', 'EventController@show');
Route::get('events/detail/{id}','EventController@detail');