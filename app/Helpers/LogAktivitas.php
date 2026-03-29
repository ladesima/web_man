<?php

use App\Models\Aktivitas;
use App\Models\Panitia;

if (!function_exists('logAktivitas')) {
    function logAktivitas($aktivitas)
    {
        $user = auth()->user();

        if (!$user) return;

        $panitia = Panitia::first(); // sementara aman

        if (!$panitia) return;

        Aktivitas::create([
            'panitia_id' => $panitia->id,
            'aktivitas' => $aktivitas,
            'waktu' => now(),
        ]);
    }
}