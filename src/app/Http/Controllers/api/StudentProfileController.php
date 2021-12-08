<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    public function openview(Request $request)
    {
        $login_user = Auth::user();
        if($login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        $show_openview_companies = $login_user->getRecentlyEnteredCompaniesInfo();

        return [
            'recently_entered_companies' => $show_openview_companies,
            'login_user' => $login_user
        ];
    }

    public function getEnteredCompanies(Request $request)
    {
        $login_user = Auth::user();
        if($login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        //求人情報からエントリーした会社を取得
        $entered_companies = $login_user->getEnteredCompanies();

        return [
            'entered_companies' => $entered_companies,
            'login_user' => $login_user
        ];
    }

    public function getMyCompanies(Request $request)
    {
        $login_user = Auth::user();
        if($login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        //生徒自身が登録した会社を取得
        $companies = $login_user->getMyCompanies();

        return [
            'companies' => $companies,
            'login_user' => $login_user
        ];
    }
}
