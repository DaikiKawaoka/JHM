<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Company;

class CompaniesController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $companies = null;
        if($user->is_teacher){
            $companies = Company::
                        select(['companies.id','companies.name as name','prefecture','url','remarks','deadline','create_user_id','users.name as create_user_name','companies.created_at'])
                        -> join('users', 'companies.create_user_id', '=', 'users.id')
                        -> where('companies.create_user_id',$user->id)
                        -> orWhere('users.teacher_id',$user->id)
                        ->latest()
                        ->paginate(5);
        }else{
            $companies = Company::
                        select(['companies.id','companies.name as name','prefecture','url','remarks','deadline','create_user_id','users.name as create_user_name','companies.created_at'])
                        ->join('users', 'companies.create_user_id', '=', 'users.id')
                        ->where('create_user_id',$user->teacher_id)
                        ->orWhere('create_user_id',$user->id)
                        ->latest()
                        ->paginate(5);
        }
        return view('companies/index')->with('companies', $companies);
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
            'create_user_id' => $user->id,
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
        //
    }
}
