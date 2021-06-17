<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entry;
use App\Progress;

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

    public function update(Request $request , $id)
    {
        $request->validate([
            'state' => ['required','string','regex:/^[待ち|◯|×|内々定|欠席]+$/u'],
            'action_date' => ['required','date'],
            'company_id' => ['required','integer'],
        ],[
            'state.required' => '状態は必須です。',
            'state.string' => '文字列で入力してください。',
            'state.regex' => '選択欄からお選びください。',
            'action_date.required' => '実施日は必須です。',
            'action_date.date' => '日にちを入力してください。',
            'company_id.required' => '会社詳細ページから変更してください。',
            'company_id.integer' => '会社IDが不正です。',
        ]);

        $user = Auth::user();
        $company_id = $request->input('company_id');
        $state = $request->input('state');
        $action_date = $request->input('action_date');
        $entry = Entry::
                    where('user_id', $user->id)
                    ->where('company_id', $company_id)
                    ->first();
        $message = '';

        if($user->is_teacher){
            $message = 'あなたは教師なのでこの処理はできません。';
            return redirect()->route('companies.show', ['company' => $company_id])->with('status-error',$message);
        }

        if($entry){
            // 会社にエントリーしている場合
            $progress = Progress::where('id', $id)
                        ->where('user_id', $user->id)
                        ->first();
            if($progress){
                // 進捗が登録されている場合update
                $progress->state = $state;
                $progress->action_date = $action_date;
                $progress->save();
                return redirect()->route('companies.show', ['company' => $company_id])->with('status','進捗を変更しました。');
            }else{
                $message = "進捗が登録されていないのでこの処理はできません。";
                return redirect()->route('companies.show', ['company' => $company_id])->with('status-error',$message);
            }
        }else{
            return redirect()->route('companies.show', ['company' => $company_id])->with('status-error','エントリーしていないのでこの処理はできません。');
        }
    }
}
