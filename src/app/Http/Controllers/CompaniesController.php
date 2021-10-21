<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Entry;
use App\User;
use App\Progress;
use App\StundentCompany;

class CompaniesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    public function index()
    {
        $user = Auth::user();
        // dd(Auth::user()->is_teacher());
        $companies = null;
        $isEntyies = [];
        if($user->is_teacher()){
            $companies = Company::
                        select(['companies.id','companies.name as name','prefecture','url','remarks','deadline','create_user_id','users.name as create_user_name','companies.created_at'])
                        -> join('users', 'companies.create_user_id', '=', 'users.id')
                        -> where('companies.create_user_id',$user->id)
                        // -> orWhere('users.teacher_id',$user->id)
                        ->latest()
                        ->paginate(5);
        }else{
            $companies = Company::
                        select(['companies.id','companies.name as name','prefecture','url','remarks','deadline','create_user_id','users.name as create_user_name','companies.created_at'])
                        ->join('users', 'companies.create_user_id', '=', 'users.id')
                        // ->where('create_user_id',$user->teacher_id)
                        ->orWhere('create_user_id',$user->id)
                        ->latest()
                        ->paginate(5);
        }
        foreach($companies as $company){
            $entries[$company->id] = Entry::
            where('student_id', $user->id)
            ->where('company_id', $company->id)
            ->first();
        }
        return view('companies/index')->with(['companies'=>$companies,'user' => $user, 'entries' => $entries]);
        return view('companies/index')->with('user', $user);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return view('companies.create');

    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $login_user = Auth::user();
        if($login_user->is_teacher()){

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'prefecture' => ['nullable', 'string', 'max:3'],
                'url' => ['url', 'nullable', 'max:255'],
                'deadline' => ['date', 'nullable','after:yesterday'],
                'remarks' => ['string','nullable'],
            ],[
                'name.required' => '会社名は必須項目です。',
                'name.max' => '会社名は必須項目です。',
                'deadline.after' => '締切日は本日以降にしてください。',
            ]);

            Company::create([
                'name' => $request->input('name'),
                'prefecture' => $request->input('prefecture'),
                'url' => $request->input('url'),
                'remarks' => $request->input('remarks'),
                'deadline' => $request->input('deadline'),
                'create_user_id' => $login_user->id,
            ]);

        }else{

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'prefecture' => ['nullable', 'string', 'max:3'],
            ],[
                'name.required' => '会社名は必須項目です。',
                'name.max' => '会社名は必須項目です。',
            ]);

            StundentCompany::create([
                'name' => $request->input('name'),
                'prefecture' => $request->input('prefecture'),
                'create_student_id' => $login_user->id,
            ]);

        }

        return redirect()->route('companies.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = Auth::user();
        // $user = User::find($user->id);
        $company = Company::find($id);
        $entry = $user->getMyEntry($company->id);
        $progress_list = null;
        // エントリーしているか分岐\
        if($entry){
            $progress_list = Progress::
                    where('user_id', $user->id)
                    ->where('entry_id', $entry->id)
                    ->orderBy('action_date','asc')
                    ->get();
        }
        return view('companies.show')->with([
            "company" => $company,
            "entry" => $entry,
            "progress_list" => $progress_list,
            "user" => $user,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $login_user = Auth::user();
        $company = Company::find($id);
        $create_company_user = User::join('companies', 'users.id', '=', 'companies.create_user_id')
                                ->where('companies.id',$id)->first();
        if($company){
            if($company->create_user_id == $login_user->id || ($login_user->is_teacher && $login_user->id == $create_company_user->teacher_id) ){
                return view('companies.edit')->with([
                    'company' => $company,
                ]);
            }
        }
        return redirect()->route('home');
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prefecture' => ['nullable', 'string', 'max:3'],
            'url' => ['url', 'nullable', 'max:255'],
            'deadline' => ['date', 'nullable','after:yesterday'],
            'remarks' => ['string','nullable'],
        ],[
            'name.required' => '会社名は必須項目です。',
            'name.max' => '会社名は必須項目です。',
            'deadline.after' => '締切日は本日以降にしてください。',
        ]);

        $login_user = Auth::user();
        $company = Company::find($id);
        $create_company_user = User::join('companies', 'users.id', '=', 'companies.create_user_id')
                                ->where('companies.id',$id)->first();
        $session_name = '';
        $session_message = '';
        if($company){
            if($company->create_user_id == $login_user->id || ($login_user->is_teacher && $login_user->id == $create_company_user->teacher_id) ){
                $company->name = $request->name;
                $company->url = $request->url;
                $company->prefecture = $request->prefecture;
                $company->deadline = $request->deadline;
                $company->remarks = $request->remarks;
                $company->save();
                $session_name = 'status';
                $session_message = '会社情報（'.$company->name.'）を変更しました。';
            }else{
                $session_name = 'status-error';
                $session_message = 'あなたは変更可能なユーザではないので変更できませんでした。';
            }
        }else{
            $session_name = 'status-error';
            $session_message = '会社情報が存在しないため、処理が失敗しました。';
        }
        return redirect()->route('companies.show', ['company' => $company->id])->with($session_name,$session_message);
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
        $company = Company::find($id);
        $create_company_user = User::join('companies', 'users.id', '=', 'companies.create_user_id')
                                ->where('companies.id',$id)->first();
        $session_name = '';
        $session_message = '';

        if($company){
            // 削除対象の会社が存在する場合
            if($company->create_user_id == $login_user->id || ($login_user->is_teacher && $login_user->id == $create_company_user->teacher_id) ){
                // 会社の作成者がログインユーザーの場合 または (ログインユーザーが先生 かつ 会社作成生徒の先生IDがログインユーザーIDの場合)
                Company::destroy($id);
                $session_name = 'status';
                $session_message = '会社情報（'.$company->name.'）を削除しました。';
            }else{
                $session_name = 'status-error';
                $session_message = 'あなたは削除可能ユーザーではないため、処理が失敗しました。';
            }
        }else{
            $session_name = 'status-error';
            $session_message = '会社情報が存在しないため、処理が失敗しました。';
        }
        return redirect()->back()->with($session_name,$session_message);
    }
}
