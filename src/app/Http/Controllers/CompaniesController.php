<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Entry;
use App\User;
use App\Progress;
use App\StudentCompany;

class CompaniesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    public function index(Request $request)
    {
        $login_user = Auth::user();
        $is_teacher = $login_user->is_teacher();

        // 会社一覧取得
        $company = new Company();

            $companies = $company->getAllCompanies();
        


        // 各会社のエントリー情報取得
        foreach($companies as $company){
            $entries[$company->id] = Entry::
            where('student_id', $login_user->id)
            ->where('company_id', $company->id)
            ->first();
        }

        return view('companies/index')->with(['companies'=>$companies,'user' => $login_user, 'entries' => $entries, 'is_teacher' => $is_teacher]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

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
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

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
        $login_user = Auth::user();
        $company = Company::find($id);
        $status = $login_user->getMyProgressByCompany($id);

        if(!$company)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        $entry = null;

        if(!$login_user->is_teacher()){
            $entry = $login_user->getEntry($company->id);
        }
        $progress_list = null;
        // エントリーしているか分岐\
        if($entry){
            $progress_list = $entry->getProgressList();
        }
        $entered_companies = $login_user->getEnteredCompanies();
        return view('companies.show')->with([
            "status" => $status,
            "company" => $company,
            "entry" => $entry,
            "progress_list" => $progress_list,
            'entered_companies' => $entered_companies,
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

        if(!$company)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        if(!$login_user->is_teacher() || $company->create_user_id != $login_user->id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        return view('companies.edit')->with('company', $company,);

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
        $company = Company::find($id);

        if(!$company)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        if(!$login_user->is_teacher() || $company->create_user_id != $login_user->id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

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

        $company->name = $request->input('name');
        $company->prefecture = $request->input('prefecture');
        $company->url = $request->input('url');
        $company->remarks = $request->input('remarks');
        $company->deadline = $request->input('deadline');

        $company->save();

        return redirect()->route('companies.show', $company->id)->with('status','会社情報を更新しました');
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

        if(!$company)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        if(!$login_user->is_teacher() || $company->create_user_id != $login_user->id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        $company->delete();

        return redirect()->route('companies.index')->with('status', '会社情報を削除しました');
    }
}
