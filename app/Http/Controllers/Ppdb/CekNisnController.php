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

        // cek apakah nisn sudah pernah digunakan
        $exists = PpdbUser::where('nisn', $nisn)->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'NISN sudah pernah digunakan untuk mendaftar'
            ]);
        }

        return response()->json([
            'status' => true,
            'nisn' => $nisn,
            'nama' => 'Calon Peserta'
        ]);
    }
}