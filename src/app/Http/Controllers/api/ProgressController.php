<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\WorkSpaces;
use Illuminate\Support\Facades\Cookie;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if(!$user->is_teacher()){
            // 先生ではない場合ホームにページ遷移
            return redirect()->route('home');
        }

        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);

        if($workspace == null){
            $workspace = $user->getTaughtClass();
            $workspace_id = $workspace->id;
            Cookie::queue('workspace_id', $workspace_id, 1000000);
            //Vueを取り入れた後に消す
            $request->session()->put('workspace_id', $workspace_id);
        }

        // 生徒配列
        $students = $user->getStudents($workspace_id);

        // 生徒で一番エントリーした人のエントリー数
        // エントリーがクラス全体で0でも1列は作成するため,1を代入
        $most_many_entry_num = 1;

        $entries_list=[];
        foreach($students as $index => $student){
            $entries_list[$index] = $student->getEntries();

            // 現在most_many_entry_numを上回る生徒がいた場合、その値をmost_many_entry_numに入れる
            if($most_many_entry_num < count($entries_list[$index])){
                $most_many_entry_num = count($entries_list[$index]);
            }
        }
        $progress_list=[];
        $progress = null;

        foreach($entries_list as $i => $entries){
            foreach($entries as $j => $entry){
                $progress[$j] = $entry->getProgressList();
            }
            $progress_list[$i] = $progress;
            $progress = null;
        }

        $MAX_PROGRESS_COUNT = config('const.MAX_PROGRESS_COUNT'); //デフォルト値:5
        $ENTRY_COLUMN_WIDTH_PX = $MAX_PROGRESS_COUNT * 100;  // 1進捗セル:100px

        // テーブル全体の幅 = 最大エントリー数 * エントリー列の幅 + 名前列の幅 + 出席番号列の幅
        $table_width_px = $most_many_entry_num * $ENTRY_COLUMN_WIDTH_PX + 100 + 65;

        return [
            'workspace' => $workspace,
            'login_user' => $user,
            'students' => $students,
            'entries_list' => $entries_list,
            'progress_list' => $progress_list,
            'most_many_entry_num' => $most_many_entry_num,
            'table_width_px' => $table_width_px,
            'entry_column_width_px' => $ENTRY_COLUMN_WIDTH_PX,
            'max_progress_count' => $MAX_PROGRESS_COUNT,
        ];
    }


    public function getEntries(Request $request)
    {
        $user = Auth::user();
        if(!$user->is_teacher()){
            // 先生ではない場合ホームにページ遷移
            return redirect()->route('home');
        }

        $workspace_id = Cookie::get('workspace_id');
        $workspace = WorkSpaces::find($workspace_id);
        if($workspace == null){
            $workspace = $user->getTaughtClass();
            $workspace_id = $workspace->id;
            Cookie::queue('workspace_id', $workspace_id, 1000000);
            //Vueを取り入れた後に消す
            $request->session()->put('workspace_id', $workspace_id);
        }

        // 生徒配列
        $students = $user->getStudents($workspace_id);

        // 生徒で一番エントリーした人のエントリー数
        // エントリーがクラス全体で0でも1列は作成するため,1を代入
        $most_many_entry_num = 1;
        $entries_list=[];
        if($request->path() == 'api/progress/getEntries'){
            // 各生徒の全エントリーを取得
            foreach($students as $index => $student){
                $entries_list[$index] = $student->getEntries();
                // 現在most_many_entry_numを上回る生徒がいた場合、その値をmost_many_entry_numに入れる
                if($most_many_entry_num < count($entries_list[$index])){
                    $most_many_entry_num = count($entries_list[$index]);
                }
            }
        }else if($request->path() == 'api/progress/getSuccessfulEntries'){
            // 各生徒の内定が出たエントリーのみ取得
            foreach($students as $index => $student){
                $entries_list[$index] = $student->getSuccessfulEntries();

                if($most_many_entry_num < count($entries_list[$index])){
                    $most_many_entry_num = count($entries_list[$index]);
                }
            }
        }else{
            // 各生徒の進行中のエントリーのみ取得
            foreach($students as $index => $student){
                $entries_list[$index] = $student->getOngoingEntries();
                if($most_many_entry_num < count($entries_list[$index])){
                    $most_many_entry_num = count($entries_list[$index]);
                }
            }
        }

        $progress_list=[];
        $progress = null;

        foreach($entries_list as $i => $entries){
            foreach($entries as $j => $entry){
                $progress[$j] = $entry->getProgressList();
            }
            $progress_list[$i] = $progress;
            $progress = null;
        }

        $MAX_PROGRESS_COUNT = config('const.MAX_PROGRESS_COUNT'); //デフォルト値:5
        $ENTRY_COLUMN_WIDTH_PX = $MAX_PROGRESS_COUNT * 100;  // 1進捗セル:100px

        // テーブル全体の幅 = 最大エントリー数 * エントリー列の幅 + 名前列の幅 + 出席番号列の幅
        $table_width_px = $most_many_entry_num * $ENTRY_COLUMN_WIDTH_PX + 100 + 65;

        return [
            'entries_list' => $entries_list,
            'progress_list' => $progress_list,
            'most_many_entry_num' => $most_many_entry_num,
            'table_width_px' => $table_width_px,
        ];
    }
}
