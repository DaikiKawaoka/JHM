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
Route::get('/', 'HomeController@index')->middleware('auth');

Route::get('/register_confirm', 'Auth\RegisterController@confirm')->name('register_confirm');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
