<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Progress;

class Entry extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id','company_id'
    ];
    protected $table = 'entries';

    public function getProgressList()
    {
        return Progress::select('progress.id','user_id','entry_id','action','state','action_date')
                ->join('users', 'progress.user_id', '=', 'users.id')
                ->where('entry_id', $this->id)
                ->orderBy('action_date','asc')
                ->get();
    }
}
