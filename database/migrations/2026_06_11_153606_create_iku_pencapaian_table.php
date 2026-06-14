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
        Schema::create('iku_pencapaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_iku')->constrained('iku')->onDelete('cascade');
            $table->foreignId('id_prodi')->constrained('prodi')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->integer('tahun');
            $table->string('target');
            $table->string('satuan');
            $table->decimal('realisasi', 10, 2)->default(0);
            $table->string('objek');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('Belum Tercapai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iku_pencapaian');
    }
};
