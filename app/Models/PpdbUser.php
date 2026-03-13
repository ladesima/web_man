<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbUser extends Model
{
    protected $table = 'ppdb_users';

    protected $fillable = [
        'nisn',
        'nama',
        'email',
        'password'
    ];
}