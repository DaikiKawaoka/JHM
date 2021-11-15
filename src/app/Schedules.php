<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    protected $fillable = [
        'workspace_id', 'content', 'schedule_date',
    ];

    protected $table = 'schedules';
}
