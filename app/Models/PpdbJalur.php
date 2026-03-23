<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbJalur extends Model
{
    protected $table = 'ppdb_jalurs';

    protected $fillable = [
        'master_ppdb_id',
        'jalur',
        'gelombang',
        'kuota',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active'
    ];

    public function tahapans()
    {
        return $this->hasMany(PpdbTahapan::class, 'jalur_id');
    }

    public function master()
    {
        return $this->belongsTo(MasterPpdb::class, 'master_ppdb_id');
    }
}