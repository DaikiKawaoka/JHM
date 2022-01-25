<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'create_user_name', 'prefecture', 'url', 'remarks', 'deadline', 'create_user_id', 'image_path',
    ];
    protected $dates = ['deadline'];
    protected $table = 'companies';

    public function getAllCompanies()
    {
        return Company::select(['companies.id','companies.name as name','prefecture','url','remarks','deadline', 'image_path',
                        'create_user_id','users.name as create_user_name','companies.created_at'])
                        ->join('users', 'companies.create_user_id', '=', 'users.id')
                        ->latest()
                        ->paginate(6);
    }

    public function getEntryCount()
    {
        return Entry::where('company_id', $this->id)->count();
    }

    public function getClassEntry($workspace_id)
    {
        $workspaces = WorkSpaces::find($workspace_id)->getMember();
        $students = [];
        foreach($workspaces as $workspace){
            array_push($students, $workspace->student_id);
        }
        $entry = Entry::select('student_id')
                        ->where('company_id', $this->id)
                        ->whereIn('student_id', $students)->get();
        return Students::whereIn('id', $entry)->get();
    }
}
