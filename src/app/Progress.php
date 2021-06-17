<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = [
        'user_id','entry_id','action','state','action_date'
    ];
    protected $dates = ['action_date'];
    protected $table = 'progress';
}
