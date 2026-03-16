<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {

            $table->string('akta_lahir')->nullable();
            $table->string('kartu_keluarga')->nullable();
            $table->string('rapor')->nullable();
            $table->string('verifikasi_pd')->nullable();
            $table->string('sertifikat_prestasi')->nullable();
            $table->string('sk_sekolah')->nullable();



        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'akta_lahir',
                'kartu_keluarga',
                'rapor',
                'verifikasi_pd',
                'sertifikat_prestasi',
                'sk_sekolah',
            ]);
        });
    }
};