<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Panitia;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use function logAktivitas; // 🔥 IMPORT HELPER

class PanitiaController extends Controller
{
    public function index()
    {
        $panitias = Panitia::latest()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'email' => $item->email,
                'username' => $item->username,
                'status' => $item->status,

                // 🔥 tampilkan password asli (dari plain_password)
                'password' => $item->plain_password ?? '-',

                // 🔥 format waktu
                'last_login' => $item->last_login
                    ? Carbon::parse($item->last_login)->diffForHumans()
                    : '-',
            ];
        });

        return view('admin.ppdb.manajemen.akun-panitia', compact('panitias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:panitias,email',
            'username' => 'required|unique:panitias,username',
            'password' => 'required|min:6',
            'status' => 'required'
        ]);

        $panitia = Panitia::create([
    'nama' => $request->nama,
    'email' => $request->email,
    'username' => $request->username,
    'password' => Hash::make($request->password),
    'plain_password' => $request->password,
    'status' => $request->status
]);

// 🔥 TAMBAH INI
logAktivitas('Menambahkan akun panitia: ' . $panitia->nama);

        return back()->with('success', 'Akun berhasil dibuat');
    }

    public function update(Request $request, $id)
    {
        $panitia = Panitia::findOrFail($id);

        $panitia->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'status' => $request->status,
        ]);

        // 🔥 FIX: update password + plain_password
        if ($request->password) {
            $panitia->password = Hash::make($request->password);
            $panitia->plain_password = $request->password; // 🔥 WAJIB
            $panitia->save();
        }
        logAktivitas('Mengupdate akun panitia: ' . $panitia->nama);

        return back()->with('success', 'Akun berhasil diupdate');
    }

    public function destroy($id)
    {
        $panitia = Panitia::findOrFail($id);

logAktivitas('Menghapus akun panitia: ' . $panitia->nama);

$panitia->delete();

        return response()->json(['success' => true]);
    }
}