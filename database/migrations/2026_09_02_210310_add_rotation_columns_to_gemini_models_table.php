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
        Schema::table('gemini_models', function (Blueprint $table) {
            $table->timestamp('last_used_at')->nullable()->after('status');
            $table->timestamp('cooldown_until')->nullable()->after('last_used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gemini_models', function (Blueprint $table) {
            $table->dropColumn(['last_used_at', 'cooldown_until']);
        });
    }
};
