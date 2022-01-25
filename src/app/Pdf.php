<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pdf extends Model
{
    protected $fillable = [
        'pdf', 'company_id'
    ];
    protected $table = 'pdf';

    public function saveAsImage($pdf_name){
        //PDFの１ページ目を画像として保存する処理
        $pdf_path = storage_path('app/public/pdf/'.$pdf_name.'.pdf');
        $pdf = new \Spatie\PdfToImage\Pdf($pdf_path);
        //PDFの１ページのみ画像に変換
        $pdf->setPage(1);
        $image_path = storage_path('app/public/pdf_image/'.$pdf_name.'.jpg');
        $pdf->saveImage($image_path);
    }

    public function cutExtension($file_name){
        $file_name_count = strlen($file_name);
        return substr($file_name, 0, ($file_name_count - 4));
    }

    public function deleteCompanyPdf($company_id){
        $company_pdf = Pdf::where('company_id', $company_id)->get();
        if($company_pdf){
            foreach ($company_pdf as $pdf) {
                Storage::delete('public/pdf/'.$pdf->pdf.'.pdf');
                $pdf->delete();
            }
        }
    }

}
