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
        Schema::table('file_isi_bukti', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->after('nama_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_isi_bukti', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
