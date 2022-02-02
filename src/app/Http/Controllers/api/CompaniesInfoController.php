<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Company;
use App\Entry;
use App\User;
use App\Progress;
use App\StudentCompany;

class CompaniesInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    public function getCompanies(Request $request)
    {
        $login_user = Auth::user();

        $search_name = $request->input('company_name');
        $search_prefe = $request->input('prefecture');
        $search_sort = $request->input('order');

        
        if($search_name != '' || $search_prefe != '' || $search_sort != '' ){
            $get_search_companies = Company::getSearchCompanies($search_name, $search_prefe, $search_sort);
        }else{
            $get_search_companies = Company::getAllCompanies();
        }

        return [
            'search_companies' => $get_search_companies,
            'login_user' => $login_user
        ];
    }
}
