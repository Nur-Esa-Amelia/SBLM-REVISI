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
        Schema::create('penilaian_rekomendasi_ai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rekomendasi_ai')->constrained('rekomendasi_ai')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->integer('total_klaim')->default(0);
            $table->integer('tp')->default(0);
            $table->integer('fp')->default(0);
            $table->integer('fn')->default(0);
            $table->float('precision', 8, 2)->default(0);
            $table->float('recall', 8, 2)->default(0);
            $table->float('f1_score', 8, 2)->default(0);
            $table->boolean('has_hallucination')->default(false);
            $table->timestamps();
        });

        Schema::create('penilaian_klaim_ai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penilaian')->constrained('penilaian_rekomendasi_ai')->onDelete('cascade');
            $table->string('nomor_klaim');
            $table->text('teks_klaim');
            $table->enum('status_penilaian', ['faktual', 'halusinasi'])->default('faktual');
            $table->timestamps();
        });

        Schema::create('penilaian_fn_ai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penilaian')->constrained('penilaian_rekomendasi_ai')->onDelete('cascade');
            $table->text('fakta_terlewat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_fn_ai');
        Schema::dropIfExists('penilaian_klaim_ai');
        Schema::dropIfExists('penilaian_rekomendasi_ai');
    }
};
