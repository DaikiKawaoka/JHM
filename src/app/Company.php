<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','create_user_name', 'prefecture', 'url','remarks','deadline','create_user_id'
    ];

    protected $dates = ['deadline'];

    protected $table = 'companies';

    public static function getAllCompanies()
    {
        return Company::select(['companies.id','companies.name as name','prefecture','url','remarks','deadline',
                        'create_user_id','users.name as create_user_name','companies.created_at'])
                        ->join('users', 'companies.create_user_id', '=', 'users.id')
                        ->orderBy('companies.id', 'desc')
                        ->paginate();
    }

    public static function getSearchCompanies($search_name, $search_prefe ,$search_sort)
    {
        return Company::select(['companies.id','companies.name as name','prefecture','url','remarks','deadline',
                        'create_user_id','users.name as create_user_name','companies.created_at'])
                        ->join('users', 'companies.create_user_id', '=', 'users.id')
                        ->where('companies.name', 'like', '%' . $search_name . '%')
                        ->where('prefecture', 'like', '%' . $search_prefe . '%')
                        ->orderBy('companies.id', $search_sort)
                        ->paginate();
    }
}
