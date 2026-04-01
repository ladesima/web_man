<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPendaftaran;
use App\Models\PpdbUser;
use App\Models\Dokumen;
use App\Models\PpdbJalur;

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
        'jumlah_saudara',
        'status',
        'akta_lahir',
        'kartu_keluarga',
        'rapor',
        'verifikasi_pd',
        'sertifikat_prestasi',
        'sk_sekolah',
        'last_step',
        'foto',
        'catatan_revisi',
        'verifikasi_dokumen',
        'is_publish',
        'email_status'
    ];
    protected $casts = [
    'verifikasi_dokumen' => 'array',
    'is_publish' => 'boolean',
];
    public function details()
{
    return $this->hasMany(DetailPendaftaran::class);
}
    public function user()
{
    return $this->belongsTo(PpdbUser::class, 'user_id');
}
public function syarat()
{
    return $this->belongsTo(\App\Models\PpdbSyarat::class, 'syarat_id');
}
public function jalur()
{
    return $this->belongsTo(\App\Models\Jalur::class, 'jalur_id');
}
}