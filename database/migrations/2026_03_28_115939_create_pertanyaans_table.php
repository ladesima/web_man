<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pertanyaans', function (Blueprint $table) {
        $table->id();
        $table->string('pertanyaan');
        $table->string('pengirim');
        $table->string('email');
        $table->string('kategori')->nullable();
        $table->enum('status', ['belum_dijawab', 'sudah_dijawab'])->default('belum_dijawab');
        $table->text('jawaban')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaans');
    }
};
