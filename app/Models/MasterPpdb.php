<?php

namespace App\Models;

use App\Models\PpdbJalur;
use App\Models\PpdbSyarat;
use App\Models\PpdbTahapan;
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
public function tahapans()
{
    return $this->hasManyThrough(
        \App\Models\PpdbTahapan::class,
        \App\Models\PpdbJalur::class,
        'master_ppdb_id', // FK di jalur
        'jalur_id',       // FK di tahapan
        'id',             // PK di master
        'id'              // PK di jalur
    );
}
public static function aktifWithRelasi()
{
    return self::with([
        'jalurs',
        'tahapans',
        'syarats'
    ])
    ->where('is_active', 1)
    ->latest()
    ->first();
}
}