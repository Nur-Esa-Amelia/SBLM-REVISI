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
        Schema::create('pengisian_bukti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_iku')->constrained('iku')->onDelete('cascade');
            $table->foreignId('id_bukti_iku')->constrained('bukti_iku')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->integer('tahun');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('pending');
            $table->text('catatan_validator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengisian_bukti');
    }
};
