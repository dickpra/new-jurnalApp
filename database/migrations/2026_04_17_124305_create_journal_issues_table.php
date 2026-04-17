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
        Schema::create('journal_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_theme_id')->constrained()->cascadeOnDelete(); // Relasi ke Tema
            $table->string('volume'); // Contoh: "Volume 1"
            $table->string('issue'); // Contoh: "Nomor 2"
            $table->integer('year'); // Contoh: 2026
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_issues');
    }
};
