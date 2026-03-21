<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPpdb extends Model
{
    protected $table = 'master_ppdb';

    protected $fillable = [
        'tahun_ajaran',
        'is_active',
        'gelombang'
    ];
}