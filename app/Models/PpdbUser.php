<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PpdbUser extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'ppdb_users';

    protected $fillable = [
        'nisn',
        'nama',
        'email',
        'password',
        'foto',
        'otp',
        'otp_expired_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}