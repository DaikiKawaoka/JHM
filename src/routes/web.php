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

Route::get('/', 'ProgressController@index');
Route::post('/auth_logout', 'LogoutController@auth_logout')->name('auth_logout');
Route::post('/progress/excel_export', 'ProgressController@excel_export')->name('progress.excel_export');

Route::get('/register_confirm', 'Auth\RegisterController@confirm')->name('register_confirm');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UsersController');
Route::put('users/updateStudentProfile/{id}', 'UsersController@updateStudentProfile')->name('users.updateStudentProfile');
Route::put('users/updateTeacherProfile/{id}', 'UsersController@updateTeacherProfile')->name('users.updateTeacherProfile');
Route::put('users/updatePassword/{id}', 'UsersController@updatePassword')->name('users.updatePassword');
Route::resource('companies', 'CompaniesController');

Route::get('student/companies/create', 'StudentCompaniesController@create')->name('studentCompanies.create');
Route::get('student/companies/{id}', 'StudentCompaniesController@show')->name('studentCompanies.show');
Route::post('student/companies', 'StudentCompaniesController@store')->name('studentCompanies.store');
Route::put('student/companies/{id}', 'StudentCompaniesController@update')->name('studentCompanies.update');
Route::delete('student/companies/{id}', 'StudentCompaniesController@destroy')->name('studentCompanies.destroy');
Route::get('student/companies/{id}/edit', 'StudentCompaniesController@edit')->name('studentCompanies.edit');

Route::resource('entries', 'EntriesController');
Route::resource('progress', 'ProgressController', ['only' => ['index','store','update','destroy']]);
Route::get('students/login', 'StudentsController@showLoginForm')->name('students.login');
Route::post('students/authenticate', 'StudentsController@authenticate')->name('students.authenticate');

Route::prefix('workspaces')->group(function(){
    Route::get('create', 'WorkSpacesController@create')->name('workspaces.create');
    Route::post('store', 'WorkSpacesController@store')->name('workspaces.store');
    Route::get('{id}/edit', 'WorkSpacesController@edit')->name('workspaces.edit');
    Route::put('{id}/update', 'WorkSpacesController@update')->name('workspaces.update');
    Route::delete('{id}/destroy', 'WorkSpacesController@destroy')->name('workspaces.destroy');
    Route::get('{id}/change', 'WorkSpacesController@change')->name('workspaces.change');
    Route::get('showMember', 'WorkSpacesController@showMember')->name('workspaces.showMember');
});
Route::get('/home', 'HomeController@index')->name('home');
