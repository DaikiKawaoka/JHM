<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entry;
use App\Progress;
use App\User;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if(!($user->is_teacher)){
            // 先生ではない場合ホームにページ遷移
            return redirect()->route('home');
        }
        // 生徒配列
        $students = User::select(['id','name','attend_num'])
                        ->where('teacher_id',$user->id)
                        ->orderBy('attend_num')
                        ->get();
        // 生徒で一番エントリーした人のエントリー数
        $most_many_entry_count = DB::select ('SELECT MAX(cnt) AS count
                                        FROM (SELECT COUNT(*) cnt FROM entries e,users u
                                        WHERE e.user_id = u.id AND u.teacher_id = :teacherid
                                        GROUP BY e.user_id) num',["teacherid"=> $user->id]);

        // 配列で帰ってくるので変換
        $most_many_entry_count = $most_many_entry_count[0]->count;

        // エントリーがクラス全体で0でも1列は作成するため,0の場合1を代入
        if($most_many_entry_count < 1){
            $most_many_entry_count = 1;
        }

        $MAX_PROGRESS_COUNT = config('const.MAX_PROGRESS_COUNT'); //デフォルト値:5
        $ENTRY_COLUMN_WIDTH_PX = $MAX_PROGRESS_COUNT * 100;  // 1進捗セル:100px

        // テーブルの幅 = 最大エントリー数 * エントリー列の幅 + 名前列の幅 + 出席番号列の幅
        $table_width_px = $most_many_entry_count * $ENTRY_COLUMN_WIDTH_PX + 100 + 65;

        return view('progress/index')->with([
            'students' => $students,
            'most_many_entry_count' => $most_many_entry_count,
            'table_width_px' => $table_width_px,
            'entry_column_width_px' => $ENTRY_COLUMN_WIDTH_PX,
            'max_progress_count' => $MAX_PROGRESS_COUNT,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'action' => ['required','string','regex:/^[会社説明会|試験受験|面接|社長面接]+$/u'],
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
        $session_name = '';
        $session_message = '';
        $MAX_PROGRESS_COUNT = config('const.MAX_PROGRESS_COUNT');

        if($user->is_teacher){
            $session_name = 'status-error';
            $session_message = 'あなたは教師なので進捗登録できません。';
            return redirect()->route('companies.show', ['company' => $company_id])->with($session_name,$session_message);
        }

        if($entry){
            // 会社にエントリーしている場合
            $progress = Progress::
                    where('user_id', $user->id)
                    ->where('entry_id', $entry->id)->get();
            if(!($progress) || $progress->count() < $MAX_PROGRESS_COUNT){
                // 同じ進捗が登録されていない場合
                Progress::create([
                    'user_id' => $user->id,
                    'entry_id' => $entry->id,
                    'action' => $action,
                    'state' => $state,
                    'action_date' => $action_date,
                ]);
                $session_name = 'status';
                $session_message = '進捗を登録しました。';
            }else{
                $session_name = 'status-error';
                $session_message = "進捗は" . $MAX_PROGRESS_COUNT . "件までしか登録することができません。";
            }
        }else{
            $session_name = 'status-error';
            $session_message = 'エントリーしていないので進捗を登録できません。';
        }
        return redirect()->route('companies.show', ['company' => $company_id])->with($session_name,$session_message);
    }

    public function update(Request $request , $progress_id)
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
        $session_name = '';
        $session_message = '';

        if($user->is_teacher){
            $session_name = 'status-error';
            $session_message = 'あなたは教師なのでこの処理はできません。';
            return redirect()->route('companies.show', ['company' => $company_id])->with($session_name,$session_message);
        }

        if($entry){
            // 会社にエントリーしている場合
            $progress = Progress::where('id', $progress_id)
                        ->where('user_id', $user->id)
                        ->first();
            if($progress){
                // 進捗が登録されている場合update
                $progress->state = $state;
                $progress->action_date = $action_date;
                $progress->save();

                $session_name = 'status';
                $session_message = '進捗を変更しました。';
            }else{
                $session_name = 'status-error';
                $session_message = "進捗が登録されていないのでこの処理はできません。";
            }
        }else{
            $session_name = 'status-error';
            $session_message = "エントリーしていないのでこの処理はできません。";
        }
        return redirect()->route('companies.show', ['company' => $company_id])->with($session_name,$session_message);
    }

    public function destroy($progress_id)
    {
        $user = Auth::user();
        $progress = Progress::find($progress_id);
        $session_name = '';
        $session_message = '';
        if($progress){
            if($user->id != $progress->user_id){
                // 自分の進捗IDではない場合
                $session_name = 'status-error';
                $session_message = '他人の進捗は削除できません。';
            }else{
                // 進捗削除処理
                Progress::destroy($progress->id);
                $session_name = 'status';
                $session_message = '進捗（'.$progress->action.'）を削除しました。';
            }
        }else{
            $session_name = 'status-error';
            $session_message = '進捗の削除処理に失敗しました。';
        }
        return redirect()->back()->with($session_name,$session_message);
    }
}