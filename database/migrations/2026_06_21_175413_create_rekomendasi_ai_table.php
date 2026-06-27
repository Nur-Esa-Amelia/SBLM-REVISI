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
        Schema::create('rekomendasi_ai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_iku_pencapaian')->constrained('iku_pencapaian')->onDelete('cascade');
            $table->longText('rekomendasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_ai');
    }
};
