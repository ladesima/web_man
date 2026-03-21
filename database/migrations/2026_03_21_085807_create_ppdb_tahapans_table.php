<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_tahapans', function (Blueprint $table) {
            $table->id();

            // 🔗 relasi ke jalur
            $table->foreignId('ppdb_jalur_id')
                ->constrained('ppdb_jalurs')
                ->cascadeOnDelete();

            // 🔥 nama tahapan
            $table->enum('nama_tahapan', [
                'pendaftaran',
                'seleksi',
                'pengumuman',
                'daftar_ulang'
            ]);

            // 🔥 tanggal
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_tahapans');
    }
};