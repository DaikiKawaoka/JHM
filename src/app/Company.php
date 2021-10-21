<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','create_user_name', 'prefecture', 'url','remarks','deadline','create_user_id'
    ];
    protected $dates = ['deadline'];
    protected $table = 'companies';

    public function getAllCompanies()
    {
        return Company::select(['companies.id','companies.name as name','prefecture','url','remarks','deadline',
                        'create_user_id','users.name as create_user_name','companies.created_at'])
                        ->join('users', 'companies.create_user_id', '=', 'users.id')
                        ->latest()
                        ->paginate(5);
    }
}
