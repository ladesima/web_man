<?php

namespace App\Models;

use App\Models\PpdbJalur;
use App\Models\PpdbSyarat;
use Illuminate\Database\Eloquent\Model;

class MasterPpdb extends Model
{
    protected $table = 'master_ppdb';

    protected $fillable = [
        'tahun_ajaran',
        'is_active',
        'gelombang'
    ];

    // 🔥 RELASI KE JALUR
    public function syarats()
{
    return $this->hasMany(PpdbSyarat::class, 'master_id');
}
public function jalurs()
{
    return $this->hasMany(PpdbJalur::class, 'master_ppdb_id');
}
}