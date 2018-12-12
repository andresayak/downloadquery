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

Route::get('/', 'IndexController@index')->name('index');
Route::get('/create', 'IndexController@create')->name('create');
Route::post('/', 'IndexController@store')->name('store');
Route::get('/download/{id}', 'IndexController@download')->name('download');

