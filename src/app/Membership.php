<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'workspace_id','student_id'
    ];

    public $timestamps = false;

    protected $table = 'membership';
}
