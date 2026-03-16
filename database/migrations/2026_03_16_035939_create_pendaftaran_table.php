<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->string('jalur');

            $table->string('nama_lengkap');
            $table->string('ttl');
            $table->string('nisn');
            $table->string('asal_sekolah');
            $table->text('alamat');

            $table->string('nama_ortu');
            $table->string('pekerjaan_ortu');
            $table->string('penghasilan_ortu');
            $table->text('alamat_ortu');
            $table->integer('jumlah_saudara');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
