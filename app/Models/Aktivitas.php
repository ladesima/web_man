<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aktivitas extends Model
{
    protected $table = 'aktivitas';

    protected $fillable = [
        'panitia_id',
        'aktivitas',
        'waktu'
    ];

    // 🔥 kalau pakai kolom waktu custom
    public $timestamps = false;

    // 🔥 casting biar otomatis jadi datetime
    protected $casts = [
        'waktu' => 'datetime',
    ];

    // 🔥 RELASI WAJIB FIX
    public function panitia(): BelongsTo
    {
        return $this->belongsTo(Panitia::class, 'panitia_id');
    }
}