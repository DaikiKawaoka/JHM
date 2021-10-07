<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entry;
use App\Company;
use App\User;

class EntriesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if(!($user->is_teacher)){
            $entered_companies = User::find($user->id)->companies;
            return view('entries/index')->with('entered_companies', $entered_companies);
        }
        // 教師はエントリー一覧ページに遷移できない
        return redirect()->route('home');
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
                    where('user_id', $user->id)
                    ->where('company_id', $company_id)
                    ->first();
        $message = '';

        if($user->is_teacher){
            $message = 'あなたは教師なのでエントリーできません。';
        }else{
            $company = Company::find($company_id);
            if($is_entered){
                $message = '過去にあなたは'.$company->name.'にエントリー済みです。';
            }else{
                Entry::create([
                    'user_id' => $user->id,
                    'company_id' => $company_id,
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
        $user = Auth::user();
        $entry = Entry::find($id);
        if(!($user->is_teacher)){
            if($entry){
                //エントリーしていれば
                $entry -> delete();
            }else{
                $message = 'あなたはエントリーしていないのでこの処理はできません。';
            }
            return redirect()->route('companies.index')->with('status-error',$message);;
        }else{
            $message = 'あなたは教師なのでこの処理はできません。';
            return redirect()->route('home')->with('status-error',$message);
        }

    }
}
