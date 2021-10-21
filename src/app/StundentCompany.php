<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StundentCompany extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'prefecture', 'create_student_id'
    ];
    protected $table = 'student_companies';
}
