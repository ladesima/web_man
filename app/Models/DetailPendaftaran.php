<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPendaftaran extends Model
{
    protected $table = 'detail_pendaftarans';

    protected $fillable = [
        'pendaftaran_id',
        'syarat_id',
        'value',
        'file'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function syarat()
    {
        return $this->belongsTo(PpdbSyarat::class, 'syarat_id');
    }
}