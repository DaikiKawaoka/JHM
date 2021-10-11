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
