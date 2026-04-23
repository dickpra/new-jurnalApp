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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->index();
            // Gunakan nullable agar tetap jalan meskipun GeoIP gagal mendeteksi
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->timestamps(); // Ini otomatis mencatat waktu kunjungan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
