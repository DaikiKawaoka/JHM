<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Entry;


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

    public function getMyEntries()
    {
        return Entry::select('entries.id','entries.user_id','entries.company_id','companies.name')
                ->join('users', 'entries.user_id', '=', 'users.id')
                ->join('companies', 'entries.company_id', '=', 'companies.id')
                ->where('users.id', $this->id)
                ->orderBy('entries.id', 'asc')
                ->get();
    }

    public function getMyEntry($company_id)
    {
        return Entry::select('entries.id','entries.user_id','entries.company_id','companies.name')
                ->join('users', 'entries.user_id', '=', 'users.id')
                ->join('companies', 'entries.company_id', '=', 'companies.id')
                ->where('users.id', $this->id)
                ->where('companies.id', $company_id)
                ->first();
    }
}