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
    Schema::table('ppdb_users', function (Blueprint $table) {
        $table->string('otp')->nullable();
        $table->timestamp('otp_expired_at')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('ppdb_users', function (Blueprint $table) {
        $table->dropColumn(['otp', 'otp_expired_at']);
    });
}
};
