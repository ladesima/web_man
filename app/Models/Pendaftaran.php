<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'jalur',
        'nama_lengkap',
        'ttl',
        'nisn',
        'asal_sekolah',
        'alamat',
        'nama_ortu',
        'pekerjaan_ortu',
        'penghasilan_ortu',
        'alamat_ortu',
        'jumlah_saudara'
    ];
}