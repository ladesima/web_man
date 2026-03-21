<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ppdb_syarats', function (Blueprint $table) {
    $table->id();

    // 🔥 WAJIB SAMA DENGAN TABLE MASTER
    $table->unsignedBigInteger('master_id');

    $table->string('nama');
    $table->string('tipe');
    $table->string('format')->nullable();
    $table->string('ukuran')->nullable();
    $table->enum('kebutuhan', ['wajib','opsional']);

    $table->timestamps();

    // 🔥 FOREIGN KEY
    $table->foreign('master_id')
          ->references('id')
          ->on('master_ppdb')
          ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_syarats');
    }
};
