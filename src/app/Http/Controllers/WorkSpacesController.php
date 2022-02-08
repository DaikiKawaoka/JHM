<?php

namespace App\Http\Controllers;

use App\WorkSpaces;
use App\Students;
use App\Membership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Rules\WorkSpaceYear;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;



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
        $validator = Validator::make($request->all(), [
            'class_name' => ['required', 'string', 'max:31'],
            'year' => ['required', new WorkSpaceYear],
        ],[
            'class_name.required' => '名前は必須項目です',
            'class_name.max' => '名前は31文字までです'
        ]);

        if ($validator->fails()){
            return redirect(route('workspaces.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $workspace = WorkSpaces::create([
            'teacher_id' => $login_user->id,
            'class_name' => $request->input('class_name'),
            'year' => $request->input('year'),
        ]);
        Cookie::queue('workspace_id', $workspace->id, 1000000);
        $request->session()->put('workspace_id', $workspace->id);
        return redirect()->route('workspaces.createStudentsShow');
    }

    public function edit($id, Request $request){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        //ワークスペース作成者でなければ、アクセスさせない
        $workspace = WorkSpaces::find($id);
        if(!$workspace)
            return redirect()->route('companies.index')->with('status-error', 'ワークスペースが存在しません');
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $now = Carbon::now();
        $this_year = $now->year;
        $years = [];
        for($i = -3; $i < 4; $i++){
            array_push($years, $this_year - $i);
        }
        return view('workspaces.edit')->with(['workspace' => $workspace, 'years' => $years, 'this_year' => $this_year]);
    }

    public function update($id, Request $request){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('progress.index')->with('status-error', 'アクセス権限がありません');
        //ワークスペース作成者でなければ、アクセスさせない
        $workspace = WorkSpaces::find($id);
        if(!$workspace)
            return redirect()->route('companies.index')->with('status-error', 'ワークスペースが存在しません');
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('progress.index')->with('status-error', 'アクセス権限がありません');
        $validator = Validator::make($request->all(), [
            'class_name' => ['required', 'string', 'max:31'],
            'year' => ['required', new WorkSpaceYear],
        ],[
            'class_name.required' => '名前は必須項目です',
            'class_name.max' => '名前は31文字までです'
        ]);

        if ($validator->fails()){
            return redirect(route('workspaces.edit', $workspace->id))
                        ->withErrors($validator)
                        ->withInput();
        }

        $workspace->class_name = $request->input('class_name');
        $workspace->year = $request->input('year');
        $workspace->save();
        return redirect()->route('progress.index')->with('status', 'ワークスペースの情報を更新しました');
    }

    public function destroy($id, Request $request){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('progress.index')->with('status-error', 'アクセス権限がありません');
        //ワークスペース作成者でなければ、アクセスさせない
        $workspace = WorkSpaces::find($id);
        if(!$workspace)
            return redirect()->route('companies.index')->with('status-error', 'ワークスペースが存在しません');
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('progress.index')->with('status-error', 'アクセス権限がありません');
        //所属している生徒をワークスペースから削除する
        Membership::where('workspace_id', $id)->delete();
        $workspace->delete();
        $first_workspace = WorkSpaces::where('teacher_id', $login_user->id)->first();
        if($first_workspace){
            $request->session()->put('workspace_id', $first_workspace->id);
            Cookie::queue('workspace_id', $first_workspace->id, 1000000);
        }else{
            $request->session()->put('workspace_id', null);
            Cookie::queue('workspace_id', null, 1000000);
        }
        return redirect()->route('progress.index')->with('status', 'ワークスペースの削除に成功しました');
    }

    public function change($id, Request $request){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        //ワークスペース作成者でなければ、アクセスさせない
        $workspace = WorkSpaces::find($id);
        if(!$workspace)
            return redirect()->route('companies.index')->with('status-error', 'ワークスペースが存在しません');
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        Cookie::queue('workspace_id', $id, 1000000);
        //Vueを取り入れた後に消す
        $request->session()->put('workspace_id', $id);
        return back();
    }

    public function showMember(){
        //切り替え中のワークスペースに所属している生徒の一覧を表示する
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);
        if(!$workspace)
            return redirect()->route('companies.index')->with('status-error', '管理しているワークスペースが存在しません');
        //ワークスペース作成者でなければ、アクセスさせない
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $member = $workspace->getMember();
        return view('workspaces.showMember')->with(['member' => $member,'workspace_id' => $workspace_id]);

    }

    public function addStudentsShow($workspace_id){
        //ワークスペースに生徒を追加するときのページ
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        if(!WorkSpaces::find($workspace_id))
            return redirect()->route('companies.index')->with('status-error', '管理しているワークスペースが存在しません');
        $students = Students::all();
        $added_students_id = \DB::table('membership')->select('student_id')->where('workspace_id',$workspace_id)->pluck('student_id')->toArray();

        return view('workspaces.addStudentsShow')->with(['students' => $students, 'added_students_id' => $added_students_id]);
    }

    public function createStudentsShow(){
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);
        if(!$workspace)
            return redirect()->route('companies.index')->with('status-error', 'ワークスペースが存在しません');
        if ($workspace->getMember()) {
            return redirect()->route('workspaces.addStudentsShow', $workspace_id);
        }

        $students = Students::all();

        return view('workspaces.createWorkspaceStudents')->with(['students' => $students]);
    }

    public function calendar(Request $request){
        $login_user = Auth::user();
        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);
        if(!$workspace)
            return redirect()->route('companies.index')->with('status-error', 'ワークスペースを登録後に利用できます');
        //ワークスペース作成者でなければ、アクセスさせない
        if($login_user->id != $workspace->teacher_id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');
        $progress = $login_user->getMemberProgress($workspace_id);
        $schedule = $workspace->getWorkspaceSchedule();
        return view('workspaces.calendar')->with([
            'progress' => $progress,
            'schedule' => $schedule,
            'is_teacher' => $login_user->is_teacher()
        ]);
    }

    public function addStudents(Request $request){
        $login_user = Auth::user();
        $workspace_id = Cookie::get('workspace_id');
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        $added_students_id = \DB::table('membership')->select('student_id')->where('workspace_id',$workspace_id)->pluck('student_id')->toArray();

        $students = $request->students;
        if(!$students){
            $students = [];
        }
        foreach ($students as $student_id) {
            if (!in_array($student_id, $added_students_id)) {
                membership::create([
                    'workspace_id' => $workspace_id,
                    'student_id' => $student_id,
                ]);
            }
        }

        foreach ($added_students_id as $added_student_id) {
            if (!in_array($added_student_id, $students)) {
                membership::where('student_id', $added_student_id)->delete();
            }
        }

        return redirect()->route('workspaces.showMember');
    }

    public function createWorkspaceStudents(Request $request){
        $login_user = Auth::user();
        $workspace_id = Cookie::get('workspace_id');
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        if (!$request->students) {
            return redirect()->back()->with('status-error', '生徒が選択されていません');
        }

        foreach ($request->students as $student_id) {
            membership::create([
                'workspace_id' => $workspace_id,
                'student_id' => $student_id,
            ]);
        }

        return redirect()->route('workspaces.showMember')->with('status',"生徒を追加しました。");
    }
}
