<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id', 'name', 'create_user_name', 'prefecture', 'url', 'remarks', 'deadline', 'create_user_id', 'image_path',
    ];

    protected $dates = ['deadline'];

    protected $table = 'companies';

    public static function getAllCompanies()
    {
        return Company::select(['companies.id','companies.name as name','prefecture','url','remarks','deadline', 'image_path',
                        'create_user_id','users.name as create_user_name','companies.created_at'])
                        ->join('users', 'companies.create_user_id', '=', 'users.id')
                        ->orderBy('companies.id', 'desc')
                        ->paginate();
    }

    public static function getSearchCompanies($search_name, $search_prefe ,$search_sort)
    {
        return Company::select(['companies.id','companies.name as name','prefecture','url','remarks','deadline', 'image_path',
                        'create_user_id','users.name as create_user_name','companies.created_at'])
                        ->join('users', 'companies.create_user_id', '=', 'users.id')
                        ->where('companies.name', 'like', '%' . $search_name . '%')
                        ->where('prefecture', 'like', '%' . $search_prefe . '%')
                        ->orderBy('companies.id', $search_sort)
                        ->paginate();
    }

    public function getEntryCount()
    {
        return Entry::where('company_id', $this->id)->count();
    }

    public function getClassEntry($workspace_id)
    {
        $member = Membership::where('workspace_id', $workspace_id)->get();
        $students = [];
        foreach($member as $student){
            array_push($students, $student->student_id);
        }
        $entry = Entry::select('student_id')
                        ->where('company_id', $this->id)
                        ->whereIn('student_id', $students)->get();
        return Students::whereIn('id', $entry)->get();
    }
}
