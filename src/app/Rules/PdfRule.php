<?php

namespace App\Rules;

use App\Company;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class PdfRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $file_name = $value->getClientOriginalName();
        //ファイル名から拡張子を切り取る
        $file_name_count = strlen($file_name);
        $extension = substr($file_name, ($file_name_count - 4), $file_name_count);
        if($extension != '.pdf')
            return false;
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'このPDF名はすでに存在しているまたは、正しいファイルではありません';
    }
}
