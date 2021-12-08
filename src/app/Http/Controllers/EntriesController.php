<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entry;
use App\Company;
use App\User;
use App\Progress;

class EntriesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    public function index()
    {

        $login_user = Auth::user();
        $entry = null;
        $progress_list = array();


        if($login_user->is_teacher()){
            // 教師はエントリー一覧ページに遷移できない
            return redirect()->route('home');
        }
        $entered_companies = $login_user->getEnteredCompanies();
        $entered_student_companies = $login_user->getMyCompanies();
        if(!$entered_companies)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        foreach ($entered_companies as $entered_company) {
            if(!$login_user->is_teacher()){
                $entry = $login_user->getEntry($entered_company->id);
            }
            // エントリーしているか分岐\
            if($entry){
                foreach($entry->getProgressList() as $provis) {
                    array_push($progress_list, array($entered_company->id=>$provis));
                }
            }
        }

        return view('entries/index')
        ->with(['entered_companies' => $entered_companies,"entered_student_companies" => $entered_student_companies, "entry" => $entry,"progress_list" => $progress_list,]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('companies/create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $company_id = $request->input('company_id');
        $is_entered = Entry::
                    where('student_id', $user->id)
                    ->where('company_id', $company_id)
                    ->first();
        $message = '';

        if($user->is_teacher()){
            $message = 'あなたは教師なのでエントリーできません。';
        }else{
            $company = Company::find($company_id);
            if($is_entered){
                $message = '過去にあなたは'.$company->name.'にエントリー済みです。';
            }else{
                Entry::create([
                    'student_id' => $user->id,
                    'company_id' => $company->id,
                ]);
                return redirect()->route('companies.show',['company' => $company_id])->with('status',$company->name.'にエントリーしました。');
            }
        }
        return redirect()->route('companies.index')->with('status-error',$message);
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
        $message = '';
        $login_user = Auth::user();
        $entry = Entry::find($id);
        if(!($login_user->is_teacher())){
            if($entry){
                //エントリーしていれば
                $entry -> delete();
            }else{
                $message = 'あなたはエントリーしていないのでこの処理はできません。';
            }
            return redirect()->route('entries.index')->with('status-error',$message);;
        }else{
            $message = 'あなたは教師なのでこの処理はできません。';
            return redirect()->route('home')->with('status-error',$message);
        }

    }
}
