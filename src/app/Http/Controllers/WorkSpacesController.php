<?php

namespace App\Http\Controllers;

use App\WorkSpaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class WorkSpacesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web']);
    }

    public function change($id, Request $request){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        Cookie::queue('workspace_id', $id, 1000000);
        //Vueを取り入れた後に消す
        $request->session()->put('workspace_id', $id);
        return back();
    }

    public function showMember(){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);
        $member = $workspace->getMember();
        return view('workspaces.showMember')->with(['member' => $member]);
    }
}
