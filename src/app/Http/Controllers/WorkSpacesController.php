<?php

namespace App\Http\Controllers;

use App\WorkSpaces;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Rules\WorkSpaceYear;


class WorkSpacesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web']);
    }

    public function create(){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $now = Carbon::now();
        $this_year = $now->year;
        $years = [];
        for($i = -3; $i < 4; $i++){
            array_push($years, $this_year - $i);
        }
        return view('workspaces.create')->with(['years' => $years, 'this_year' => $this_year]);
    }

    public function store(Request $request){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $request->validate([
            'name' => ['required', 'string', 'max:31'],
            'year' => ['required', new WorkSpaceYear],
        ],[
            'name.max' => '名前は31文字までです'
        ]);
        $workspace = WorkSpaces::create([
            'teacher_id' => $login_user->id,
            'class_name' => $request->input('name'),
            'year' => $request->input('year'),
        ]);
        return redirect()->route('companies.index');
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
