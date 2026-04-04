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
    Schema::table('pendaftaran', function (Blueprint $table) {
        $table->integer('nilai_rapor')->nullable();
        $table->integer('nilai_prestasi')->nullable();
    });
}

public function down()
{
    Schema::table('pendaftaran', function (Blueprint $table) {
        $table->dropColumn(['nilai_rapor', 'nilai_prestasi']);
    });
}
};
