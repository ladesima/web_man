<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {

            // foto peserta
            $table->string('foto')->nullable()->after('nama_lengkap');

            // catatan dari admin
            $table->text('catatan_revisi')->nullable()->after('status');

            // json untuk status tiap dokumen
            $table->json('verifikasi_dokumen')->nullable()->after('catatan_revisi');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'foto',
                'catatan_revisi',
                'verifikasi_dokumen'
            ]);
        });
    }
};