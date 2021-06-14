<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id','company_id'
    ];
    protected $table = 'entries';
}
