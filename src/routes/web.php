<?php

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\WorkSpacesController;
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

Route::get("/", "ProgressController@index");
Route::get("/progress/index2", "ProgressController@index2");
Route::post("/auth_logout", "LogoutController@auth_logout")->name(
    "auth_logout"
);
Route::get("/progress/excel_export", "ProgressController@excel_export")->name(
    "progress.excel_export"
);

Route::get("/register_confirm", "Auth\RegisterController@confirm")->name(
    "register_confirm"
);
Auth::routes();
Route::get("/home", "HomeController@index")->name("home");

Route::resource("users", "UsersController");
Route::put(
    "users/updateStudentProfile/{id}",
    "UsersController@updateStudentProfile"
)->name("users.updateStudentProfile");
Route::put(
    "users/updateTeacherProfile/{id}",
    "UsersController@updateTeacherProfile"
)->name("users.updateTeacherProfile");
Route::put("users/updatePassword/{id}", "UsersController@updatePassword")->name(
    "users.updatePassword"
);

//companyに関連するアクセス方法
Route::resource("companies", "CompaniesController");
Route::get(
    "companies/{id}/download_pdf",
    "CompaniesController@downloadPdf"
)->name("companies.download_pdf");

Route::get(
    "student/companies/create",
    "StudentCompaniesController@create"
)->name("studentCompanies.create");
Route::get(
    "student/companies/identityRegister",
    "StudentCompaniesController@identityRegister"
)->name("studentCompanies.identityRegister");
Route::get("student/companies/{id}", "StudentCompaniesController@show")->name(
    "studentCompanies.show"
);
Route::post("student/companies", "StudentCompaniesController@store")->name(
    "studentCompanies.store"
);
Route::put("student/companies/{id}", "StudentCompaniesController@update")->name(
    "studentCompanies.update"
);
Route::delete(
    "student/companies/{id}",
    "StudentCompaniesController@destroy"
)->name("studentCompanies.destroy");
Route::get(
    "student/companies/{id}/edit",
    "StudentCompaniesController@edit"
)->name("studentCompanies.edit");

Route::resource("entries", "EntriesController");
Route::resource("progress", "ProgressController", [
    "only" => ["index", "store", "update", "destroy"],
]);
Route::get("students/login", "StudentsController@showLoginForm")->name(
    "students.login"
);
Route::get("students/create", "StudentsController@create")->name(
    "students.create"
);
Route::post("students", "StudentsController@store")->name(
    "students.store"
);
Route::post("students/authenticate", "StudentsController@authenticate")->name(
    "students.authenticate"
);

Route::group(["middleware" => "auth:web,student"], function () {
    Route::get("students/show", "StudentsController@show")->name(
        "students.show"
    );
});

Route::group(['prefix' => 'api'], function(){
    Route::get('/progress', 'api\ProgressController@index');
    Route::get('/progress/getEntries', 'api\ProgressController@getEntries');
    Route::get('/progress/getSuccessfulEntries', 'api\ProgressController@getEntries');
    Route::get('/progress/getOngoingEntries', 'api\ProgressController@getEntries');
    Route::get('/student/overview', 'api\StudentProfileController@overview');
    Route::get('/student/getMyCompanies', 'api\StudentProfileController@getMyCompanies');
    Route::get('/student/getEnteredCompanies', 'api\StudentProfileController@getEnteredCompanies');
    Route::get('/companies/getCompanies', 'api\CompaniesInfoController@getCompanies');
});

Route::prefix("workspaces")->group(function () {
    Route::get("create", "WorkSpacesController@create")->name(
        "workspaces.create"
    );
    Route::post("store", "WorkSpacesController@store")->name(
        "workspaces.store"
    );
    Route::get("{id}/edit", "WorkSpacesController@edit")->name(
        "workspaces.edit"
    );
    Route::put("{id}/update", "WorkSpacesController@update")->name(
        "workspaces.update"
    );
    Route::delete("{id}/destroy", "WorkSpacesController@destroy")->name(
        "workspaces.destroy"
    );
    Route::get("{id}/change", "WorkSpacesController@change")->name(
        "workspaces.change"
    );
    Route::get("showMember", "WorkSpacesController@showMember")->name(
        "workspaces.showMember"
    );
    Route::get("calendar", "WorkSpacesController@calendar")->name(
        "workspaces.calendar"
    );
    Route::get("addStudentsShow", "WorkSpacesController@addStudentsShow")->name(
        "workspaces.addStudentsShow"
    );
    Route::get(
        "createStudentsShow",
        "WorkSpacesController@createStudentsShow"
    )->name("workspaces.createStudentsShow");
    Route::post("addStudents", "WorkSpacesController@addStudents")->name(
        "workspaces.addStudents"
    );
    Route::post(
        "createWorkspaceStudents",
        "WorkSpacesController@createWorkspaceStudents"
    )->name("workspaces.createWorkspaceStudents");
});

Route::prefix("schedule")->group(function () {
    Route::post("store", "CompanyScheduleController@store")->name('schedule.store');
    Route::post("{id}/update", "CompanyScheduleController@update")->name('schedule.update');
    Route::delete("{id}/destroy", "CompanyScheduleController@destroy")->name('schedule.destroy');
    Route::get("calendar", "CompanyScheduleController@calendar")->name(
        "schedules.calendar"
    );
});

Route::delete(
    "/companies/{company_id}/pdf/{pdf_id}/destroy",
    "CompaniesController@removePdf"
)->name("companies.remove_pdf");

// =============== fallback 404 ===============
// Route::fallback(function () {
// 	return redirect('/');
// });

Route::get("/home", "HomeController@index")->name("home");
