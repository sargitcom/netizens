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

Route::match(['get', 'post'], '/', ['uses' => 'ApplicationController@index']);

Route::match(['post'], '/download-images', ['uses' => 'ApplicationController@downloadImages']);

Route::match(['get'], '/images-list/{page}', ['uses' => 'ApplicationController@listImages']);
Route::match(['get'], '/images-list', ['uses' => 'ApplicationController@listImages']);