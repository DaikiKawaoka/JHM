<?php

namespace App\Http\Controllers;

// Spreadsheet
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\WorkSpaces;
use App\Entry;
use App\Progress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Response;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment as Align;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;   // 塗りつぶし種類
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ProgressController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    // 先生のルートページ
    public function ajax_index(Request $request)
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
        $most_many_entry_num = $user->getMostManyEntryNum($workspace_id);

        $MAX_PROGRESS_COUNT = config('const.MAX_PROGRESS_COUNT'); //デフォルト値:5
        $ENTRY_COLUMN_WIDTH_PX = $MAX_PROGRESS_COUNT * 100;  // 1進捗セル:100px

        // テーブル全体の幅 = 最大エントリー数 * エントリー列の幅 + 名前列の幅 + 出席番号列の幅
        $table_width_px = $most_many_entry_num * $ENTRY_COLUMN_WIDTH_PX + 100 + 65;

        $entries_list=[];
        foreach($students as $index => $student){
            $entries_list[$index] = $student->getMyEntries();
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

        // return view('progress/index')->with([
        //     'workspace' => $workspace,
        //     'students' => $students,
        //     'most_many_entry_num' => $most_many_entry_num,
        //     'table_width_px' => $table_width_px,
        //     'entry_column_width_px' => $ENTRY_COLUMN_WIDTH_PX,
        //     'max_progress_count' => $MAX_PROGRESS_COUNT,
        // ]);
    }

    public function index(Request $request){
        $login_user = Auth::user();
        if(!$login_user->is_teacher()){
            // 先生ではない場合ホームにページ遷移
            return redirect()->route('home');
        }
        return view('progress/index');
    }

    public function index2(Request $request){
        $user = Auth::user();
        // dd($user);
        if(!$user->is_teacher()){
            // 先生ではない場合ホームにページ遷移
            // dd($user);
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
        $most_many_entry_num = $user->getMostManyEntryNum($workspace_id);

        $MAX_PROGRESS_COUNT = config('const.MAX_PROGRESS_COUNT'); //デフォルト値:5
        $ENTRY_COLUMN_WIDTH_PX = $MAX_PROGRESS_COUNT * 100;  // 1進捗セル:100px

        // テーブル全体の幅 = 最大エントリー数 * エントリー列の幅 + 名前列の幅 + 出席番号列の幅
        $table_width_px = $most_many_entry_num * $ENTRY_COLUMN_WIDTH_PX + 100 + 65;

        return view('progress/index2')->with([
            'workspace' => $workspace,
            'students' => $students,
            'most_many_entry_num' => $most_many_entry_num,
            'table_width_px' => $table_width_px,
            'entry_column_width_px' => $ENTRY_COLUMN_WIDTH_PX,
            'max_progress_count' => $MAX_PROGRESS_COUNT,
        ]);
    }

    public function store(Request $request)
    {
        $login_user = Auth::user();

        if($login_user->is_teacher()){
            return redirect()->route('companies.index')->with('status-error','処理権限がありません');
        }

        $request->validate([
            'action' => ['required','string','regex:/^[説明会|試験受験|面接|社長面接]+$/u'],
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

        $company_id = $request->input('company_id');
        $action = $request->input('action');
        $state = $request->input('state');
        $action_date = $request->input('action_date');
        $entry = null;
        if ($request->input('company_type') == 'teacher_created_company') {
            //求人情報の会社のエントリー
            $entry = $login_user->getEntry($company_id);
        }else{
            //生徒自身が登録した会社のエントリー
            $entry = $login_user->getMyCompanyEntry($company_id);
        }

        $session_name = '';
        $session_message = '';
        $MAX_PROGRESS_COUNT = config('const.MAX_PROGRESS_COUNT');


        if($entry){
            // 会社にエントリーしている場合
            $progress = Progress::
                    where('student_id', $login_user->id)
                    ->where('entry_id', $entry->id)->get();
            if(!($progress) || $progress->count() < $MAX_PROGRESS_COUNT){
                // 同じ進捗が登録されていない場合
                Progress::create([
                    'student_id' => $login_user->id,
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

        if ($request->input('company_type') == 'teacher_created_company') {
            //求人情報の会社のエントリー
            return redirect()->route('companies.show', ['company' => $company_id])->with($session_name,$session_message);
        }else{
            //生徒自身が登録した会社のエントリー
            return redirect()->route('studentCompanies.show', ['id' => $company_id])->with($session_name,$session_message);
        }
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

        $login_user = Auth::user();
        $company_id = $request->input('company_id');
        $state = $request->input('state');
        $action_date = $request->input('action_date');
        $entry = Entry::
                    where('user_id', $login_user->id)
                    ->where('company_id', $company_id)
                    ->first();
        $session_name = '';
        $session_message = '';

        if($login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        if($entry){
            // 会社にエントリーしている場合
            $progress = Progress::where('id', $progress_id)
                        ->where('student_id', $login_user->id)
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
        $login_user = Auth::user();
        $progress = Progress::find($progress_id);
        $session_name = '';
        $session_message = '';
        if($progress){
            if($login_user->id != $progress->student_id){
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

    public function excel_export(Request $request)
    {
        $user = Auth::user();
        if(!($user->is_teacher())){
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
        $most_many_entry_num = $user->getMostManyEntryNum($workspace_id);

        $MAX_PROGRESS_COUNT = config('const.MAX_PROGRESS_COUNT'); //デフォルト値:5

        // スプレッドシートを作成
        $spreadsheet = new Spreadsheet();
        // ファイルのプロパティを設定
        $spreadsheet->getProperties()
        ->setTitle("就職活動表");

        // シート作成
        $spreadsheet->getActiveSheet('sheet1')->UnFreezePane();
        $spreadsheet->getDefaultStyle()->getFont()->setName('游ゴシック');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("学生就職進捗表");

        $max_cell_row_num = 2 + $most_many_entry_num * $MAX_PROGRESS_COUNT;
        $max_cell_row_alphabet = Coordinate::stringFromColumnIndex($max_cell_row_num);
        $max_cell_col_num = 6 + count($students) * 4;

        // 全使用セル中央揃え
        $sheet -> getStyle("A3:{$max_cell_row_alphabet}{$max_cell_col_num}") -> getAlignment() -> setHorizontal(Align::HORIZONTAL_CENTER);
        for($i = 0, $row_val='C'; $i < $most_many_entry_num * $MAX_PROGRESS_COUNT; $i++,$row_val++){
            $sheet->getColumnDimension($row_val)->setWidth(12);
        }
        // 会社名記入セルを全てセル結合
        for($i = 0; $i < count($students); $i++){
            $cell_col_num = 7 + ($i * 4);
            for($j = 0; $j < $most_many_entry_num; $j++){
                $cell_row_num = 3 + ($j * $MAX_PROGRESS_COUNT);
                $sheet->mergeCellsByColumnAndRow($cell_row_num, $cell_col_num, $cell_row_num + ($MAX_PROGRESS_COUNT - 1), $cell_col_num);
            }
        }

        // 全使用セル格子
        $sheet->getStyle("A3:{$max_cell_row_alphabet}{$max_cell_col_num}")->getBorders()->getAllBorders()->setBorderStyle( Border::BORDER_THIN );

        // 1行目
        $sheet->mergeCells('A1:F1'); // セル結合
        $sheet->setCellValue('A1', $workspace->year.'年度 就職活動表');
        $sheet->getStyle('A1')->getFont()->setSize(20);// フォントサイズ変更
        $sheet->getRowDimension(1)->setRowHeight(33.0);// 行の高さ変更
        $sheet->getStyle('A1')->getAlignment()->setVertical( Alignment::VERTICAL_CENTER );// 垂直位置中央揃え

        // 2行目
        $sheet->mergeCells('B2:I2'); // セル結合
        $sheet->setCellValue('B2', "【河原電子ビジネス専門学校】/【{$workspace->class_name}科】/【担任：{$user->name}】");
        $sheet->getStyle('B2')->getFont()->setSize(16);
        $sheet->getRowDimension(2)->setRowHeight(33.0);// 行の高さ変更
        $sheet->getStyle('B2')->getAlignment()->setVertical( Alignment::VERTICAL_CENTER );// 垂直位置中央揃え

        // 3行目
        for($i = 0 ; $i < $most_many_entry_num; $i++){
            $write_cell_row_num = 3 + ($i * $MAX_PROGRESS_COUNT); // $iが0の場合 -> 列番号Cを取得
            $sheet->setCellValueByColumnAndRow($write_cell_row_num, 3, '応募先企業名');
            $sheet->mergeCellsByColumnAndRow($write_cell_row_num, 3, $write_cell_row_num + $MAX_PROGRESS_COUNT - 1 , 3);
        }

        // 4行目
        $sheet->mergeCells('A3:A6'); // セル結合
        $sheet->mergeCells('B3:B6');
        // 垂直位置中央揃え
        $sheet->getStyle('A3')->getAlignment()->setVertical( Alignment::VERTICAL_CENTER );
        $sheet->getStyle('B3')->getAlignment()->setVertical( Alignment::VERTICAL_CENTER );

        $sheet->setCellValue('A3', '出席番号');
        $sheet->setCellValue('B3', '学生氏名');

        $sheet->mergeCellsByColumnAndRow($write_cell_row_num, 3, $write_cell_row_num + $MAX_PROGRESS_COUNT - 1 , 3);
        $sheet->getColumnDimension( 'B' )->setWidth(17);
        for($i = 0 ; $i < $most_many_entry_num; $i++){
            for($j = 0; $j < $MAX_PROGRESS_COUNT; $j++){
                $write_cell_row_num = 3 + $j + ($i * $MAX_PROGRESS_COUNT); // $i,jが0の場合 -> 列番号3(C)を取得
                $sheet->setCellValueByColumnAndRow($write_cell_row_num, 4, '選考' . ($j+1));
            }
        }

        // 5行目
        for($i = 0 ; $i < $most_many_entry_num; $i++){
            $write_cell_row_num = 3 + ($i * $MAX_PROGRESS_COUNT);
            $sheet->setCellValueByColumnAndRow($write_cell_row_num, 5, '日付');
            // セル結合
            $sheet->mergeCellsByColumnAndRow($write_cell_row_num, 5, $write_cell_row_num + $MAX_PROGRESS_COUNT - 1 , 5);
        }

        // 6行目
        for($i = 0 ; $i < $most_many_entry_num; $i++){
            $write_cell_row_num = 3 + ($i * $MAX_PROGRESS_COUNT);
            $sheet->setCellValueByColumnAndRow($write_cell_row_num, 6, '結果');
            $sheet->mergeCellsByColumnAndRow($write_cell_row_num, 6, $write_cell_row_num + $MAX_PROGRESS_COUNT - 1 , 6);
        }

        // 生徒の進捗記入
        foreach($students as $s_index => $student){
            $write_cell_col_num = 7 + ($s_index * 4);
            // 生徒の出席番号、名前を出力
            $sheet->mergeCells("A{$write_cell_col_num}:A".($write_cell_col_num + 3));
            $sheet->mergeCells("B{$write_cell_col_num}:B".($write_cell_col_num + 3));
            // 垂直位置中央揃え
            $sheet->getStyle("A{$write_cell_col_num}")->getAlignment()->setVertical( Alignment::VERTICAL_CENTER );
            $sheet->getStyle("B{$write_cell_col_num}")->getAlignment()->setVertical( Alignment::VERTICAL_CENTER );
            $sheet->setCellValue("A{$write_cell_col_num}", $student->attend_num);
            $sheet->setCellValue("B{$write_cell_col_num}", $student->name);

            // 生徒のエントリー会社を出力
            foreach($student->getMyEntries() as $e_index => $entry){

                $write_cell_row_num = 3 + ($e_index * $MAX_PROGRESS_COUNT);
                $has_got_informal_offer = false; // 内定か
                $is_failed = false; // 不合格か

                if($entry->company_name != null){
                    // 先生が登録した会社へのエントリー
                    $sheet->setCellValueByColumnAndRow($write_cell_row_num, $write_cell_col_num, $entry->company_name);
                }else{
                    //生徒自身が登録した会社へのエントリー
                    $sheet->setCellValueByColumnAndRow($write_cell_row_num, $write_cell_col_num, $entry->student_company_name);
                }

                // 生徒の進捗情報を出力
                foreach($entry->getProgressList() as $p_index => $progress){
                    $sheet->setCellValueByColumnAndRow($write_cell_row_num + $p_index , $write_cell_col_num + 1, $progress->action);
                    $sheet->setCellValueByColumnAndRow($write_cell_row_num + $p_index , $write_cell_col_num + 2, $progress->action_date->format('Y/m/d'));
                    $sheet->setCellValueByColumnAndRow($write_cell_row_num + $p_index , $write_cell_col_num + 3, $progress->state);
                    if($progress->state == "内々定") $has_got_informal_offer = true;
                    if($progress->state == "×") $is_failed = true;
                }
                if($has_got_informal_offer){
                    // 数字からアルファベットに変換
                    $write_cell_row_alphabet = Coordinate::stringFromColumnIndex($write_cell_row_num);
                    // 採用の場合背景色を黄色に変更
                    $sheet->getStyle($write_cell_row_alphabet.($write_cell_col_num))->getFill()->setFillType( Fill::FILL_SOLID )->getStartColor()->setRGB( 'ffff00' );
                }
                if($is_failed){
                    $write_cell_row_alphabet = Coordinate::stringFromColumnIndex($write_cell_row_num);
                    // 不採用の場合背景色を灰色に変更
                    $sheet->getStyle($write_cell_row_alphabet.($write_cell_col_num))->getFill()->setFillType( Fill::FILL_SOLID )->getStartColor()->setRGB( 'a9a9a9' );
                }

            }
        }

        $fileName = "就職活動表.xlsx";

        // ダウンロード
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        return;
    }
}