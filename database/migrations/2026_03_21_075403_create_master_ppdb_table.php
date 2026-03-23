<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_ppdb', function (Blueprint $table) {
            $table->id();

            // 🔥 tahun ajar
            $table->string('tahun_ajaran'); // contoh: 2026/2027

            // 🔥 status aktif
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_ppdb');
    }
};