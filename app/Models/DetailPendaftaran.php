<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPendaftaran extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'syarat_id',
        'value',
        'file'
    ];
}