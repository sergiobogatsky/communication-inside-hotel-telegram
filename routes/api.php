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

//departments:
Route::get('/departments/', 'DepartmentsController@index');
Route::get('/departments/show/{id}', 'DepartmentsController@show');
Route::get('/departments/{id}/edit', 'DepartmentsController@edit');
Route::put('/departments/{id}', 'DepartmentsController@update');
Route::get('/departments/create', 'DepartmentsController@create');
Route::post('/departments/store', 'DepartmentsController@store');
Route::delete('/departments/{id}/destroy', 'DepartmentsController@destroy');

//employees:
Route::get('/employees/', 'EmployeesController@index');
Route::get('/employees/show/{id}', 'EmployeesController@show');
Route::get('/employees/{id}/edit', 'EmployeesController@edit');
Route::put('/employees/{id}', 'EmployeesController@update');
Route::get('/employees/create', 'EmployeesController@create');
Route::post('/employees/store', 'EmployeesController@store');
Route::delete('/employees/{id}/destroy', 'EmployeesController@destroy');

//tasks:
Route::get('/tasks/', 'TasksController@index');