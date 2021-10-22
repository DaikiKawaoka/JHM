<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class WorkSpaceYear implements Rule
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
        $this_year = Carbon::now()->year;
        //前後３年まで通す
        return ($value >= ($this_year) - 3 && $value <= ($this_year + 3));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $this_year = Carbon::now()->year;
        return '登録できる年度は、'.($this_year - 3).'〜'.($this_year + 3).'です';
    }
}
