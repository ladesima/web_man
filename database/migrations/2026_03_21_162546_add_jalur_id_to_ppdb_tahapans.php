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
    Schema::table('ppdb_tahapans', function (Blueprint $table) {
        $table->unsignedBigInteger('jalur_id')->after('id');

        // optional (best practice)
        $table->foreign('jalur_id')
              ->references('id')
              ->on('ppdb_jalurs')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('ppdb_tahapans', function (Blueprint $table) {
        $table->dropForeign(['jalur_id']);
        $table->dropColumn('jalur_id');
    });
}
};
