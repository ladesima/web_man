<?php

namespace App\Http\Controllers\Ppdb;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PpdbUser;

class CekNisnController extends Controller
{
    public function cek(Request $request)
{
    $request->validate([
        'nisn' => 'required|digits:10'
    ]);

    $nisn = $request->nisn;

    $exists = PpdbUser::where('nisn', $nisn)->exists();

    if ($exists) {
        return response()->json([
            'status' => false,
            'message' => 'NISN sudah terdaftar, silakan login'
        ]);
    }

    return response()->json([
        'status' => true,
        'nisn' => $nisn,
        'nama' => 'Calon Peserta'
    ]);
}
}