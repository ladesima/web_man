<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_jalurs', function (Blueprint $table) {
            $table->id();

            // 🔗 relasi ke master PPDB
            $table->foreignId('master_ppdb_id')
                ->constrained('master_ppdb')
                ->cascadeOnDelete();

            // 🔥 jenis jalur
            $table->enum('jalur', ['prestasi', 'reguler', 'afirmasi']);

            // 🔥 gelombang
            $table->string('gelombang'); // I, II, III

            // 🔥 kuota
            $table->integer('kuota')->default(0);

            // 🔥 status aktif
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_jalurs');
    }
};