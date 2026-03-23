<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbSyarat extends Model
{
    protected $table = 'ppdb_syarats';

    protected $fillable = [
        'master_id',
        'nama',
        'tipe',
        'format',
        'ukuran',
        'kebutuhan',
    ];

    // 🔥 RELASI KE MASTER
    public function master()
    {
        return $this->belongsTo(MasterPpdb::class, 'master_id');
    }
}