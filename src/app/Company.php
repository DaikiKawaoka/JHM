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
}
