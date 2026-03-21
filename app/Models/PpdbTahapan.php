<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbTahapan extends Model
{
    protected $table = 'ppdb_tahapans';

    protected $fillable = [
        'jalur_id',
        'nama_tahapan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
    'tanggal_mulai' => 'date:d M Y',
    'tanggal_selesai' => 'date:d M Y',
];

    /**
     * Relasi ke Jalur
     */
    public function jalur()
    {
        return $this->belongsTo(PpdbJalur::class, 'jalur_id');
    }
}