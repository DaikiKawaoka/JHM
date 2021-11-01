<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Entry;
use App\User;
use App\Progress;
use App\StudentCompany;

class StudentCompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $login_user = Auth::user();
        if($login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        return view('studentCompanies.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $login_user = Auth::user();
        if($login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prefecture' => ['nullable', 'string', 'max:3'],
        ],[
            'name.required' => '会社名は必須項目です。',
            'name.max' => '会社名は必須項目です。',
        ]);

        $student_company = new StudentCompany;
        $student_company->name = $request->input('name');
        $student_company->prefecture = $request->input('prefecture');
        $student_company->create_student_id = $login_user->id;
        $student_company->save();

        Entry::create([
            'student_id' => $login_user->id,
            'student_company_id' => $student_company->id
        ]);

        return redirect()->route('studentCompanies.show',$student_company->id);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $login_user = Auth::user();
        $student_company = StudentCompany::select(['student_companies.id as id','student_companies.name as name',
                            'prefecture','student_companies.create_student_id','students.name as create_student_name'])
                            ->join('students', 'student_companies.create_student_id', '=', 'students.id')
                            ->where('student_companies.id',$id)
                            ->first();

        if(!$student_company)
            return redirect()->route('entries.index')->with('status-error', '会社データが存在しません');

        if(!$login_user->is_teacher() && $student_company->create_student_id != $login_user->id)
            return redirect()->route('entries.index')->with('status-error', 'アクセス権限がありません');

        $entry = null;
        if(!$login_user->is_teacher()){
            $entry = $login_user->getMyCompanyEntry($student_company->id);
        }
        $progress_list = null;
        // エントリーしているか分岐\
        if($entry){
            $progress_list = $entry->getProgressList();
        }
        return view('studentCompanies.show')->with([
            "company" => $student_company,
            "entry" => $entry,
            "progress_list" => $progress_list,
            "user" => $login_user,
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
        $company = StudentCompany::find($id);

        if(!$company)
            return redirect()->back()->with('status-error', '会社データが存在しません');

        if($login_user->is_teacher() || $company->create_student_id != $login_user->id)
            return redirect()->back()->with('status-error', 'アクセス権限がありません');

        return view('studentCompanies.edit')->with('company', $company,);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $login_user = Auth::user();
        $company = StudentCompany::find($id);

        if(!$company)
            return redirect()->back()->with('status-error', '会社データが存在しません');

        if($login_user->is_teacher() || $company->create_student_id != $login_user->id)
            return redirect()->back()->with('status-error', 'アクセス権限がありません');


        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prefecture' => ['nullable', 'string', 'max:3'],
        ],[
            'name.required' => '会社名は必須項目です。',
            'name.max' => '会社名は必須項目です。',
        ]);

        $company->name = $request->input('name');
        $company->prefecture = $request->input('prefecture');

        $company->save();

        return redirect()->route('studentCompanies.show', $company->id)->with('status','会社情報を更新しました');
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
        $company = StudentCompany::find($id);

        if(!$company)
            return redirect()->back()->with('status-error', '会社データが存在しません');

        if($login_user->is_teacher() || $company->create_student_id != $login_user->id)
            return redirect()->back()->with('status-error', 'アクセス権限がありません');

        $entry = $company->getEntry();

        if($entry->hasProgress()){
            return redirect()->back()->with('status-error', '進捗情報が登録されている為、削除できません。');
        }

        $entry->delete();
        $company->delete();

        return redirect()->route('entries.index')->with('status', '会社情報を削除しました');
    }
}
