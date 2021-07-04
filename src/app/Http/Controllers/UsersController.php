<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $login_user = Auth::user();
        if($login_user->is_teacher){
            return view('users/create');
        }
        return redirect()->route('home');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $login_user = Auth::user();
        if($login_user->is_teacher){

            $session_name = '';
            $session_message = '';

            $deleted_student = User::onlyTrashed()->where('email',$request->email)->first();
            if($deleted_student){
                // 過去に削除されている場合 生徒を復元
                $deleted_student->restore();
                $session_name = 'status';
                $session_message = '生徒（'.$deleted_student->name.'）を復元しました。';
            }else{
                // 生徒を新規作成
                $request->validate([
                    'attend_num' => ['required','integer','min:1','max:50'],
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ],[
                    'name.required' => '生徒名は必須項目です。',
                    'attend_num.required' => '出席番号は必須です。',
                    'attend_num.max' => '出席番号は50より小さい数にしてください。',
                    'attend_num.min' => '出席番号は1以上です。',
                    'email.required'  => 'メールアドレスは必須項目です。',
                    'email.email'  => 'メールアドレスを入力してください。',
                    'password.required'  => 'パスワードは必須項目です。',
                ]);

                User::create([
                    'name' => $request->input('name'),
                    'attend_num' => $request->input('attend_num'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('passowrod')),
                    'is_teacher' => 0,
                    'teacher_id' => $login_user->id,
                ]);
                $session_name = 'status';
                $session_message = '生徒（'.$request->name.'）を登録しました。';
            }
        }else{
            $session_name = 'status-error';
            $session_message = 'あなたは教師ではないので生徒を登録することはできません。';
        }
        return redirect()->route('home')->with($session_name,$session_message);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $login_user = Auth::user();
        if(!($login_user->is_teacher)){
            return redirect('home');
        }

        $student = User::find($id);
        $session_name = '';
        $session_message = '';

        if($student){
            // 削除対象生徒が存在する場合
            if($student->teacher_id == $login_user->id){
                // 削除対象生徒がログインユーザーの生徒の場合
                User::destroy($id);
                $session_name = 'status';
                $session_message = '生徒（'. $student->name .'）を削除しました。';
            }else{
                // 削除対象生徒が存在するが、ログインユーザの生徒ではない場合
                $session_name = 'status-error';
                $session_message = '削除対象の生徒が自分の生徒ではない為、処理が失敗しました。';
            }
        }else{
            $session_name = 'status-error';
            $session_message = '削除対象の生徒が存在しない為、処理が失敗しました。';
        }
        return redirect()->back()->with($session_name,$session_message);
    }
}
