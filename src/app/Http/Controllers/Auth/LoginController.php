<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\WorkSpaces;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $user->login_at = now();
        $user->save();
        //選択しているワークスペースをセッションに保存する
        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::where('id', $workspace_id)->where('teacher_id', $user->id)->first();
        if($workspace){
            //クッキーに保存しているワークスペースIDのワークスペースが存在していれば、セッションに保存
            $request->session()->put('workspace_id', $workspace_id);
            Cookie::queue('workspace_id', $workspace_id, 1000000);
        }else{
            //管理しているワークスペースがあれば
            $first_workspace = WorkSpaces::where('teacher_id', $user->id)->first();
            if($first_workspace){
                $request->session()->put('workspace_id', $first_workspace->id);
                Cookie::queue('workspace_id', $first_workspace->id, 1000000);
            }
        }
    }
}
