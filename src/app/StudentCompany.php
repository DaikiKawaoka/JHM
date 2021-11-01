<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCompany extends Model
{
    protected $fillable = [
        'name', 'prefecture', 'create_student_id'
    ];
    protected $table = 'student_companies';

    public function getEntry()
    {
        return Entry::select(['entries.id'])
                ->join('student_companies', 'entries.student_company_id', '=', 'student_companies.id')
                ->where('student_companies.id', $this->id)
                ->first();
    }
}
