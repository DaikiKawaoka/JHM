<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Entry;
use App\Students;
use App\WorkSpaces;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function is_teacher()
    {
        return true;
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class,'entries','user_id','company_id');
    }

    public function getTaughtClass(){
        return WorkSpaces::where('teacher_id', $this->id)->first();
    }

    public function getTaughtClasses(){
        return WorkSpaces::where('teacher_id', $this->id)->get();
    }

    public function getStudents($workspace_id){
        return Students::select(['students.id','name','attend_num'])
                ->join('membership', 'students.id', '=', 'membership.student_id')
                ->where('workspace_id',$workspace_id)
                ->orderBy('attend_num')
                ->get();
    }
    public function getMostManyEntryNum($workspace_id){
        // ワークスペース内の生徒で一番エントリーした人のエントリー数を取得
        $most_many_entry_num = DB::select ('SELECT MAX(cnt) AS count FROM 
                            (SELECT COUNT(*) cnt FROM entries e,students s,membership m 
                            WHERE e.student_id = s.id AND s.id = m.student_id AND m.workspace_id = :workspace_id 
                            GROUP BY e.student_id) num',["workspace_id"=> $workspace_id]);
        // 配列で帰ってくるので変換
        $most_many_entry_num = $most_many_entry_num[0]->count;

        // エントリーがクラス全体で0でも1列は作成するため,0の場合1を代入
        if($most_many_entry_num < 1){
            return $most_many_entry_num = 1;
        }
        return $most_many_entry_num;
    }

    //生徒の進捗を取得する
    public function getMemberProgress($workspace_id){
        $progress = Progress::select([
            'progress.action', 'progress.state', 'progress.action_date', 'students.name'
        ])
            ->join('membership', 'membership.student_id', 'progress.student_id')
            ->join('students', 'students.id', 'progress.student_id')
            ->where('membership.workspace_id', $workspace_id)
            ->orderBy('progress.action_date')
            ->get();
        return $progress;
    }
}