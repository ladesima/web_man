<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * RUN MIGRATION
     */
    public function up(): void
    {
        Schema::create('ppdb_syarats', function (Blueprint $table) {
            $table->id();

            // 🔥 RELASI KE MASTER PPDB
            $table->unsignedBigInteger('master_id');

            // 🔥 DATA SYARAT
            $table->string('nama'); 
            // contoh: "Kartu Keluarga", "Ijazah", dll

            $table->enum('tipe', ['text', 'file']);
            // text = input biasa
            // file = upload file

            $table->string('format')->nullable();
            // contoh: pdf, jpg, png

            $table->string('ukuran')->nullable();
            // contoh: 2MB, 5MB

            $table->enum('kebutuhan', ['wajib', 'opsional'])->default('wajib');

            $table->timestamps();

            // 🔥 FOREIGN KEY
            $table->foreign('master_id')
                ->references('id')
                ->on('master_ppdb')
                ->onDelete('cascade');
        });
    }

    /**
     * ROLLBACK
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_syarats');
    }
};