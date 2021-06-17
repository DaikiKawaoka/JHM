<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entry;
use App\Company;
use App\Progress;
use App\User;
use Symfony\Component\Process\Process;

class ProgressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'state' => ['required','string'],
            'action' => ['required','string'],
            'action_date' => ['required','date'],
            'company_id' => ['required'],
        ],[
            'state.required' => '状態は必須項目です。',
            'action.required' => '活動内容は必須です。',
            'action_date.required' => '実施日は必須です。',
        ]);

        $user = Auth::user();
        $company_id = $request->input('company_id');
        $action = $request->input('action');
        $state = $request->input('state');
        $action_date = $request->input('action_date');
        $entry = Entry::
                    where('user_id', $user->id)
                    ->where('company_id', $company_id)
                    ->first();
        $progress = Progress::
                    where('user_id', $user->id)
                    ->where('entry_id', $entry->id)
                    ->where('action', $action)
                    ->first();
        $message = '';
        if($progress){
            $message = "既にその活動内容（". $progress->action ."）は登録済みです。";
            return redirect()->route('companies.show', ['company' => $company_id])->with('status-error',$message);
        }else{
            if($user->is_teacher){
                $message = 'あなたは教師なので進捗登録できません。';
                return redirect()->route('home')->with('status-error',$message);
            }else{
                if($entry){
                    Progress::create([
                        'user_id' => $user->id,
                        'entry_id' => $entry->id,
                        'action' => $action,
                        'state' => $state,
                        'action_date' => $action_date,
                    ]);
                    return redirect()->route('companies.show', ['company' => $company_id])->with('status','進捗を登録しました。');
                }
            }
        }
    }
}
