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
        Schema::create('file_isi_bukti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengisian_bukti')->constrained('pengisian_bukti')->onDelete('cascade');
            $table->string('file_bukti');
            $table->string('nama_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_isi_bukti');
    }
};
