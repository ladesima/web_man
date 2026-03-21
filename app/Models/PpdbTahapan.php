<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbTahapan extends Model
{
    protected $table = 'ppdb_tahapans';

    protected $fillable = [
        'ppdb_jalur_id',
        'nama_tahapan',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    public function jalur()
    {
        return $this->belongsTo(PpdbJalur::class, 'ppdb_jalur_id');
    }
}