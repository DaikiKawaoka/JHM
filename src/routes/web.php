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
Route::post('/progress/excel_export', 'ProgressController@excel_export')->name('progress.excel_export');

Route::get('/register_confirm', 'Auth\RegisterController@confirm')->name('register_confirm');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UsersController');
Route::put('users/updateStudentProfile/{id}', 'UsersController@updateStudentProfile')->name('users.updateStudentProfile');
Route::put('users/updateTeacherProfile/{id}', 'UsersController@updateTeacherProfile')->name('users.updateTeacherProfile');
Route::put('users/updatePassword/{id}', 'UsersController@updatePassword')->name('users.updatePassword');
Route::resource('companies', 'CompaniesController');
Route::resource('entries', 'EntriesController');
Route::resource('progress', 'ProgressController', ['only' => ['index','store','update','destroy']]);
Route::get('students/login', 'StudentsController@showLoginForm')->name('students.showLoginForm');
Route::post('students/authenticate', 'StudentsController@authenticate')->name('students.authenticate');

Route::get('/home', 'HomeController@index')->name('home');
