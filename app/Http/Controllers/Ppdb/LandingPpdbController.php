<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbUser;

class LandingPpdbController extends Controller
{
    public function index()
    {
        $user = PpdbUser::find(session('ppdb_user_id'));

        return view('ppdb.dashboard.beranda', compact('user'));
    }
}