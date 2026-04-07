<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaGambar extends Model
{
    protected $table = 'media_gambar'; // 🔥 WAJIB (FIX ERROR)
    
    protected $fillable = ['key', 'file'];
}