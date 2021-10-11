<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Students;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class StudentsController extends Controller
{

    use AuthenticatesUsers;

    protected function guard()
    {
        return Auth::guard('student');  //変更
    }

    public function showLoginForm(){

        return view('students.login');

    }

    public function authenticate(Request $request){


        $credentials = $request->only('email', 'password');

        if(Auth::guard('student')->attempt($credentials, true)) {

            $student = Students::where('email', $request->input('email'))->first();
            // dd($student);
            Auth::setUser($student, true);
            // dd(Auth::user());
            return redirect()->route('companies.index'); // ログインしたらリダイレクト

        }

        return back()->withErrors([
            'auth:student' => ['認証に失敗しました']
        ]);

    }
}
