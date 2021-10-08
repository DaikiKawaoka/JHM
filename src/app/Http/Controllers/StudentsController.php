<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{

    public function showLoginForm(){

        return view('students.login');

    }

    public function login(Request $request){


        $credentials = $request->only('email', 'password');

        dd(Auth::attempt($credentials));

        if(Auth::attempt($credentials)) {

            return redirect()->route('companies.index'); // ログインしたらリダイレクト

        }

        return back()->withErrors([
            'auth' => ['認証に失敗しました']
        ]);

    }
}
