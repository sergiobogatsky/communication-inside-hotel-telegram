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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/departments/', 'DepartmentsController@index');
Route::get('/departments/show/{id}', 'DepartmentsController@show');
Route::get('/departments/{id}/edit', 'DepartmentsController@edit');
Route::put('/departments/{id}/update', 'DepartmentsController@update');
Route::get('/departments/create', 'DepartmentsController@create');
Route::post('/departments/store', 'DepartmentsController@store');
Route::delete('/departments/{id}/destroy', 'DepartmentsController@destroy');