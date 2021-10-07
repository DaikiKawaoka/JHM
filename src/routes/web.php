<?php

use Illuminate\Support\Facades\Route;
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
Route::get('/', 'ProgressController@index')->middleware('auth');
Route::post('/progress/excel_export', 'ProgressController@excel_export')->name('progress.excel_export')->middleware('auth');

Route::get('/register_confirm', 'Auth\RegisterController@confirm')->name('register_confirm');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UsersController')->middleware('auth');
Route::put('users/updateStudentProfile/{id}', 'UsersController@updateStudentProfile')->name('users.updateStudentProfile')->middleware('auth');
Route::put('users/updateTeacherProfile/{id}', 'UsersController@updateTeacherProfile')->name('users.updateTeacherProfile')->middleware('auth');
Route::put('users/updatePassword/{id}', 'UsersController@updatePassword')->name('users.updatePassword')->middleware('auth');
Route::resource('companies', 'CompaniesController')->middleware('auth');
Route::resource('entries', 'EntriesController')->middleware('auth');
Route::resource('progress', 'ProgressController', ['only' => ['index','store','update','destroy']])->middleware('auth');
