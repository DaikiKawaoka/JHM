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
        $user = Auth::user();
        if($user->is_teacher){
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
        $user = Auth::user();
        if($user->is_teacher){
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
                'teacher_id' => $user->id,
            ]);
        }else{
            return redirect()->route('home')
            ->with('status-error','あなたは教師ではないので生徒を登録することはできません。');
        }
        return redirect()->route('home');
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
        //
    }
}
