<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function getMyEntries()
    {
        return Entry::select(['entries.id as id','entries.student_id as student_id','entries.company_id as company_id','companies.name as company_name','companies.prefecture','companies.url','entries.student_company_id as student_company_id','student_companies.name as student_company_name'])
                ->leftJoin('students', 'entries.student_id', '=', 'students.id')
                ->leftJoin('companies', 'entries.company_id', '=', 'companies.id')
                ->leftJoin('student_companies', 'entries.student_company_id', '=', 'student_companies.id')
                ->where('students.id', $this->id)->orderBy('entries.id', 'asc')->get();
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
                ->orderBy('entries.id', 'asc')
                ->get();
    }

    //自分が登録した会社一覧を取得
    public function getMyCompanies()
    {
        return StudentCompany::select(['student_companies.id','student_companies.name'])
                ->join('entries', 'student_companies.id', '=', 'entries.student_company_id')
                ->join('students', 'entries.student_id', '=', 'students.id')
                ->where('students.id', $this->id)
                ->orderBy('entries.id', 'asc')
                ->get();
    }

    public function getSchedule(){
        return Schedules::select('content', 'schedule_date')
            ->join('workspaces', 'workspaces.id', 'workspace_id')
            ->join('membership', 'workspaces.id', 'membership.workspace_id')
            ->where('membership.student_id', $this->id)
            ->get();
    }
}
