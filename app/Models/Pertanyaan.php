<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $fillable = [
        'pertanyaan',
        'pengirim',
        'email',
        'kategori',
        'status',
        'jawaban'
    ];
}
