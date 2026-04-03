<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengumumanNilaiController extends Controller
{
    public function index()
{
    $data = Pendaftaran::where('is_publish', 1)->get();

    return view('panitia.pengumuman_nilai.index', compact('data'));
}
}
