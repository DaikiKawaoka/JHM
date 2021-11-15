<?php

namespace App\Http\Controllers;

use App\Schedules;
use App\WorkSpaces;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CompanyScheduleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    public function store(Request $request){
        $login_user = Auth::user();
        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);
        if(!$login_user->is_teacher()){
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        }
        //ワークスペース管理者でなければ、アクセスさせない
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $content = $request->input('content');
        $schedule_date = $request->input('schedule_date');
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'string', 'max:31'],
            'schedule_date' => ['required', 'date', 'after:yesterday'],
        ]);
        if($validator->fails()){
            return redirect()->route('workspaces.calendar')->with('status-error', '予定日は本日以降、予定内容は31文字以内です');
        }
        Schedules::create([
            'workspace_id' => $workspace_id,
            'content' => $content,
            'schedule_date' => $schedule_date,
        ]);
        return redirect()->route('workspaces.calendar')->with('status', '予定を新規作成しました');
    }

    public function update($id, Request $request){
        $login_user = Auth::user();
        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);
        if(!$login_user->is_teacher()){
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        }
        //ワークスペース管理者でなければ、アクセスさせない
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        //更新処理
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'string', 'max:31'],
            'schedule_date' => ['required', 'date', 'after:yesterday'],
        ]);
        if($validator->fails()){
            return redirect()->route('workspaces.calendar')->with('status-error', '予定日は本日以降、予定内容は31文字以内です');
        }
        $schedule = Schedules::find($id);
        $schedule->content = $request->input('content');
        $schedule->schedule_date = $request->input('schedule_date');
        $schedule->save();
        return redirect()->route('workspaces.calendar')->with('status', '予定を更新しました');
    }

    public function destroy($id){
        $login_user = Auth::user();
        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);
        if(!$login_user->is_teacher()){
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        }
        //ワークスペース管理者でなければ、アクセスさせない
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        //削除処理
        $schedule = Schedules::find($id);
        $schedule->delete();
        return redirect()->route('workspaces.calendar')->with('status', '予定を削除しました');
    }

    public function calendar(){
        $login_user = Auth::user();
        if($login_user->is_teacher()){
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        }
        $schedule = $login_user->getSchedule();
        return view('workspaces.calendar')->with([
            'schedule' => $schedule,
            'is_teacher'=>$login_user->is_teacher()
        ]);
    }
}
