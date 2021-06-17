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
            'action' => ['required','string','regex:/^[会社説明会|試験受験|一次面接|二次面接|三次面接|社長面接]+$/u'],
            'state' => ['required','string','regex:/^[待ち|◯|×|内々定|欠席]+$/u'],
            'action_date' => ['required','date'],
            'company_id' => ['required'],
        ],[
            'state.required' => '状態は必須です。',
            'state.string' => '文字列で入力してください。',
            'state.regex' => '選択欄からお選びください。',
            'action.required' => '活動内容は必須です。',
            'action.string' => '文字列で入力してください。',
            'action.regex' => '選択欄からお選びください。',
            'action_date.required' => '実施日は必須です。',
            'action_date.date' => '日にちを入力してください。',
            'company_id.required' => '会社詳細ページから登録してください。',
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
        $message = '';

        if($user->is_teacher){
            $message = 'あなたは教師なので進捗登録できません。';
            return redirect()->route('companies.show', ['company' => $company_id])->with('status-error',$message);
        }

        if($entry){
            // 会社にエントリーしている場合
            $progress = Progress::
                    where('user_id', $user->id)
                    ->where('entry_id', $entry->id)
                    ->where('action', $action)
                    ->first();
            if(!($progress)){
                // 同じ進捗が登録されていない場合
                Progress::create([
                    'user_id' => $user->id,
                    'entry_id' => $entry->id,
                    'action' => $action,
                    'state' => $state,
                    'action_date' => $action_date,
                ]);
                return redirect()->route('companies.show', ['company' => $company_id])->with('status','進捗を登録しました。');
            }else{
                $message = "既にその活動内容（". $progress->action ."）は登録済みです。";
                return redirect()->route('companies.show', ['company' => $company_id])->with('status-error',$message);
            }
        }else{
            return redirect()->route('companies.show', ['company' => $company_id])->with('status-error','エントリーしていないので進捗を登録できません。');
        }
    }
}
