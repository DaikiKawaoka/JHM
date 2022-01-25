<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Entry;
use App\Pdf;
use App\User;
use App\Progress;
use App\StudentCompany;
use Illuminate\Support\Facades\Storage;
use App\Rules\PdfRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cookie;

class CompaniesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:web,student']);
    }

    public function index()
    {
        $login_user = Auth::user();

        // 会社一覧取得
        $company = new Company();
        $companies = $company->getAllCompanies();

        // 各会社のエントリー情報取得
        foreach($companies as $company){
            $entries[$company->id] = Entry::
            where('student_id', $login_user->id)
            ->where('company_id', $company->id)
            ->first();
        }

        return view('companies/index_test')->with(['companies'=>$companies,'user' => $login_user, 'entries' => $entries]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        return view('companies.create');

    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $login_user = Auth::user();
        if(!$login_user->is_teacher())
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prefecture' => ['nullable', 'string', 'max:3'],
            'url' => ['url', 'nullable', 'max:255'],
            'deadline' => ['date', 'nullable','after:yesterday'],
            'remarks' => ['string','nullable'],
        ],[
            'name.required' => '会社名は必須項目です。',
            'name.max' => '会社名は２５５文字以内です',
            'url.max' => 'URLは２５５文字以内です',
            'url.url' => 'URLを入力してください',
            'deadline.after' => '締切日は本日以降にしてください。',
        ]);

        $pdf_names = [];
        $image_path = '';

        for($i = 1; $i <= 3; $i++){
            if($request->file('pdf'.$i)){
                //PDFの保存処理
                $file_name = $request->file('pdf'.$i)->getClientOriginalName();
                $request->file('pdf'.$i)->storeAs('public/pdf',$file_name);
                //ファイル名から拡張子を切り取る
                $pdf_model = new Pdf();
                $pdf_name = $pdf_model->cutExtension($file_name);

                $check_data = [
                    'pdf_name' => $pdf_name,
                    'extension' => $request->file('pdf'.$i),
                ];

                $validator = Validator::make($check_data, [
                    'pdf_name' => [Rule::unique('pdf', 'pdf'), 'string', 'max:63'],
                    'extension' => [new PdfRule],
                ]);
                //バリデータに引っかかったら、前のページにリダイレクトする
                if($validator->fails()){
                    return redirect()->route('companies.create')
                        ->with('status-error', '追加しようとしたPDFまたは、PDF名はすでに存在しています')
                        ->withErrors($validator)
                        ->withInput();;
                }

                array_push($pdf_names, $pdf_name);

                if($i == 1){
                    $pdf_model->saveAsImage($pdf_name);
                    $image_path = $pdf_name;
                }
            }
        }

        $company = Company::create([
            'name' => $request->input('name'),
            'prefecture' => $request->input('prefecture'),
            'url' => $request->input('url'),
            'remarks' => $request->input('remarks'),
            'deadline' => $request->input('deadline'),
            'create_user_id' => $login_user->id,
            'image_path' => $image_path,
        ]);

        foreach ($pdf_names as $pdf) {
            Pdf::create([
                'pdf' => $pdf,
                'company_id' => $company->id
            ]);
        }

        return redirect()->route('companies.index')->with('status', '求人情報を追加しました');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $login_user = Auth::user();
        $company = Company::find($id);

        if($login_user->is_teacher()){
            //先生の場合
            $workspace_id = Cookie::get('workspace_id');
            //全生徒のうちのエントリー数
            $allEntryCount = $company->getEntryCount();
            //ワークスペースの生徒のうちのエントリー数
            $classEntry = $company->getClassEntry($workspace_id);
            return view('companies.show')->with([
                "company" => $company,
                "allEntryCount" => $allEntryCount,
                "classEntry" => $classEntry,
            ]);
        }else{
            //生徒の場合
            $status = $login_user->getMyProgressByCompany($id);

            if(!$company)
                return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

            $entry = $login_user->getEntry($company->id);
            $progress_list = null;
            // エントリーしているか分岐\
            if($entry){
                $progress_list = $entry->getProgressList();
            }
            $entered_companies = $login_user->getEnteredCompanies();
            return view('companies.show')->with([
                "status" => $status,
                "company" => $company,
                "entry" => $entry,
                "progress_list" => $progress_list,
                'entered_companies' => $entered_companies,
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $login_user = Auth::user();
        $company = Company::find($id);

        if(!$company)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        if(!$login_user->is_teacher() || $company->create_user_id != $login_user->id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        return view('companies.edit')->with('company', $company,);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $login_user = Auth::user();
        $company = Company::find($id);

        if(!$company)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        if(!$login_user->is_teacher() || $company->create_user_id != $login_user->id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

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

        $company->name = $request->input('name');
        $company->prefecture = $request->input('prefecture');
        $company->url = $request->input('url');
        $company->remarks = $request->input('remarks');
        $company->deadline = $request->input('deadline');

        $company->save();

        return redirect()->route('companies.show', $company->id)->with('status','会社情報を更新しました');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $login_user = Auth::user();
        $company = Company::find($id);

        if(!$company)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        if(!$login_user->is_teacher() || $company->create_user_id != $login_user->id)
            return redirect()->route('companies.index')->with('status-error', 'アクセス権限がありません');

        $pdf_model = new Pdf();
        $pdf_model->deleteCompanyPdf($id);
        $company->delete();

        return redirect()->route('companies.index')->with('status', '会社情報を削除しました');
    }

    public function downloadPdf($id){
        $company =  Company::find($id);

        if(!$company)
            return redirect()->route('companies.index')->with('status-error', '会社データが存在しません');

        if(!$company->image_path)
            return redirect()->route('companies.index')->with('status-error', 'PDFが存在しません');

        $pdf_count = Pdf::where('company_id', $id)->count();

        if($pdf_count == 1){
            //PDFダウンロード
            $file_name = $company->image_path.'.pdf';
            $filePath = 'public/pdf/'.$file_name;
            $mimeType = Storage::mimeType($filePath);
            $headers = [['Content-Type' => $mimeType]];
            return Storage::download($filePath, $file_name, $headers);
        }else{
            //zipに圧縮して、ダウンロード
            $now = Carbon::now();
            // $zip_name = $company->name.'.zip';
            $zip_name = $company->name.$now->year.'.zip';
            // $zip_name = $company->name.$now->year.$now->month.$now->day.$now->hour.$now->minute.$now->second.'.zip';
            $save_path = storage_path('app/public/pdf_zip/'.$zip_name);
            $zip_file = new \ZipArchive();
            $zip_file->open($save_path, \ZipArchive::CREATE);
            $pdf_files = Pdf::where('company_id', $id)->get();
            foreach($pdf_files as $pdf){
                $pdf_path = storage_path('app/public/pdf/'.$pdf->pdf.'.pdf');
                $zip_file->addFile($pdf_path, $pdf->pdf.'.pdf');
            }
            $zip_file->close();
            return response()->download($save_path);
        }

    }
}
