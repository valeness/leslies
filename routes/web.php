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

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('/product/{id}', ['as' => 'product', 'uses' => 'HomeController@product']);
Route::get('/search', ['as' => 'search', 'uses' => 'HomeController@search']);
Route::get('/analytics', ['as' => 'analytics', 'uses' => 'HomeController@analytics']);
Route::get('/api/analytics', ['as' => 'analyticsApi', 'uses' => 'HomeController@analyticsApi']);
