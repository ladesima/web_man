<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $fillable = [
        'nama',
        'nisn',
        'sekolah',
        'jalur',
        'gelombang',
        'no_pendaftaran',
        'nilai_rapor',
        'nilai_prestasi',
        'nilai_total',
        'status'
    ];

}
