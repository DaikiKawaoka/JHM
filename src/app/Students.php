<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use DateTime;

class Students extends Authenticatable{

    use Notifiable;
    use SoftDeletes;

    protected $primary_key = 'id';

    protected $guard = 'student';

    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attend_num','name', 'email', 'password',
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
        return false;
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class,'entries','user_id','company_id');
    }

    // エントリーを取得
    public function getEntries()
    {
        return Entry::select(['entries.id as id','entries.student_id as student_id','entries.company_id as company_id','companies.name as company_name','companies.prefecture','companies.url','entries.student_company_id as student_company_id','student_companies.name as student_company_name'])
                ->leftJoin('students', 'entries.student_id', '=', 'students.id')
                ->leftJoin('companies', 'entries.company_id', '=', 'companies.id')
                ->leftJoin('student_companies', 'entries.student_company_id', '=', 'student_companies.id')
                ->where('students.id', $this->id)->orderBy('entries.id', 'asc')->get();
    }

    // 内定が出されたエントリーを取得
    public function getSuccessfulEntries()
    {
        return Entry::select(['entries.id as id','entries.student_id as student_id','entries.company_id as company_id','companies.name as company_name','companies.prefecture','companies.url','entries.student_company_id as student_company_id','student_companies.name as student_company_name'])
                ->leftJoin('students', 'entries.student_id', '=', 'students.id')
                ->leftJoin('companies', 'entries.company_id', '=', 'companies.id')
                ->leftJoin('student_companies', 'entries.student_company_id', '=', 'student_companies.id')
                ->leftJoin('progress', 'entries.id', '=', 'progress.entry_id')
                ->distinct()
                ->where('students.id', $this->id)
                ->where('progress.state', '=' , '内々定')
                ->orderBy('entries.id', 'asc')->get();
    }

    // 現在進行中のエントリーを取得 (不合格と内々定が無いエントリー)
    public function getOngoingEntries()
    {
        return Entry::select(['entries.id as id','entries.student_id as student_id','entries.company_id as company_id','companies.name as company_name','companies.prefecture','companies.url','entries.student_company_id as student_company_id','student_companies.name as student_company_name'])
                ->leftJoin('students', 'entries.student_id', '=', 'students.id')
                ->leftJoin('companies', 'entries.company_id', '=', 'companies.id')
                ->leftJoin('student_companies', 'entries.student_company_id', '=', 'student_companies.id')
                ->leftJoin('progress', 'entries.id', '=', 'progress.entry_id')
                ->distinct()
                ->where('students.id', $this->id)
                ->whereNotIn('entries.id', function($q){
                    $q->select('entries.id')
                        ->from('entries')
                        ->leftJoin('progress', 'entries.id', '=', 'progress.entry_id')
                        ->where(function($q){
                                $q->where('progress.state', '=' , '不合格')
                                ->orWhere('progress.state', '=' , '内々定');
                            });
                })
                ->orderBy('entries.id', 'asc')->get();
    }

    //求人情報にある会社のエントリーを取得
    public function getEntry($company_id)
    {
        return Entry::select('entries.id')
                ->where('student_id', $this->id)
                ->where('company_id', $company_id)
                ->first();
    }

    //自分で登録した会社のエントリーを取得
    public function getMyCompanyEntry($company_id)
    {
        return Entry::select('entries.id')
                ->where('student_id', $this->id)
                ->where('student_company_id', $company_id)
                ->first();
    }

    //求人情報からエントリーした会社一覧を取得
    public function getEnteredCompanies()
    {
        return Company::select(['companies.id','companies.name'])
        ->join('entries', 'companies.id', '=', 'entries.company_id')
        ->join('students', 'entries.student_id', '=', 'students.id')
        ->where('students.id', $this->id)
        ->orderBy('entries.id', 'desc')
        ->get();
    }

    //最近エントリーした会社取得(6件)
    public function getRecentlyEnteredCompaniesInfo()
    {
        return Entry::select(['entries.id as id','entries.student_id as student_id','entries.company_id as company_id','companies.name as company_name','entries.student_company_id as student_company_id','student_companies.name as student_company_name'])
                ->leftJoin('students', 'entries.student_id', '=', 'students.id')
                ->leftJoin('companies', 'entries.company_id', '=', 'companies.id')
                ->leftJoin('student_companies', 'entries.student_company_id', '=', 'student_companies.id')
                ->where('students.id', $this->id)->orderBy('entries.id', 'desc')->limit(6)->get();
    }

    //自分が登録した会社一覧を取得
    public function getMyCompanies()
    {
        return StudentCompany::select(['student_companies.id','student_companies.name'])
                ->join('entries', 'student_companies.id', '=', 'entries.student_company_id')
                ->join('students', 'entries.student_id', '=', 'students.id')
                ->where('students.id', $this->id)
                ->orderBy('entries.id', 'desc')
                ->get();
    }

    //自分が登録している１つの会社の進捗
    public function getMyProgressByCompany($company_id)
    {
        return Progress::select(['progress.id', 'progress.action', 'progress.state', 'progress.action_date'])
        ->join('entries', 'progress.entry_id', '=', 'entries.id')
        ->where('progress.student_id', $this->id)
        ->where('entries.company_id', $company_id)
        ->get();
    }

    //自分の2年間分のエントリー数を配列で返す
    public function getMonthEntryCount()
    {
        $this_year_entry_count_array = [0,0,0,0,0,0,0,0,0,0,0,0];
        $last_year_entry_count_array = [0,0,0,0,0,0,0,0,0,0,0,0];
        $this_year  = date('Y'); //今年
        $last_year = date('Y', strtotime('-1 year')); //去年
        $entry_count_array = Entry::select(DB::raw('create_year , create_month , count(*) AS entry_count'))
                ->where('student_id', $this->id)
                ->whereBetween('create_year', [$last_year, $this_year])
                ->groupBy('create_year','create_month')
                ->get();
        foreach($entry_count_array as $entry){
            if($entry->create_year == $this_year){
                // 今年のエントリーの場合        キャスト
                $this_year_entry_count_array[((int) $entry->create_month) - 1] = $entry->entry_count;
            }else if($entry->create_year == $last_year){
                // 去年のエントリーの場合        キャスト
                $last_year_entry_count_array[((int) $entry->create_month) - 1] = $entry->entry_count;
            }
        };
        return $entry_count_array = [$this_year_entry_count_array,$last_year_entry_count_array];
    }

    public function getSchedule()
    {
        return Schedules::select('content', 'schedule_date')
            ->join('workspaces', 'workspaces.id', 'workspace_id')
            ->join('membership', 'workspaces.id', 'membership.workspace_id')
            ->where('membership.student_id', $this->id)
            ->get();
    }
}
