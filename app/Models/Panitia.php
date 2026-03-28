<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panitia extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'username',
        'password',
        'status',
        'last_login',
        'plain_password'
    ];

    protected $hidden = ['password'];
}
