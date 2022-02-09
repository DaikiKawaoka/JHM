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

    public function create()
    {
        $login_user = Auth::user();
        if(!$login_user){
            return view('students/create');
        }
        return redirect()->route('home');
    }

    public function store(Request $request)
    {
        $login_user = Auth::user();
        if(!$login_user){

            $session_name = '';
            $session_message = '';

            $deleted_student = Students::onlyTrashed()->where('email',$request->email)->first();
            if($deleted_student){
                // 過去に削除されている場合 生徒を復元
                $deleted_student->restore();
            }else{
                // 生徒を新規作成
                $request->validate([
                    'attend_num' => ['required','integer','min:1','max:50'],
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ],[
                    'name.required' => '生徒名は必須項目です。',
                    'attend_num.required' => '出席番号は必須です。',
                    'attend_num.max' => '出席番号は50より小さい数にしてください。',
                    'attend_num.min' => '出席番号は1以上です。',
                    'email.required'  => 'メールアドレスは必須項目です。',
                    'email.email'  => 'メールアドレスを入力してください。',
                    'email.unique'  => 'そのメールアドレスは使用できません。',
                    'password.required'  => 'パスワードは必須項目です。',
                    'password.confirmed'  => '確認パスワードと一致しません。',
                ]);

                Students::create([
                    'name' => $request->input('name'),
                    'attend_num' => $request->input('attend_num'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                ]);
                $session_name = 'status';
                $session_message = '生徒アカウントを作成しました。';
            }
        }
        return redirect()->route('home')->with($session_name,$session_message);
    }


    public function show(Request $request)
    {
        $login_user = Auth::user();
        if($login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        return view('students.show');
    }
}
