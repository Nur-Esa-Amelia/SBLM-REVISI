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
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_prodi')->nullable()->constrained('prodi')->onDelete('set null');
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('tahun_mulai');
            $table->integer('tahun_selesai');
            $table->integer('tahun_aktif');
            $table->integer('jml_mahasiswa');
            $table->integer('jml_dosen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
