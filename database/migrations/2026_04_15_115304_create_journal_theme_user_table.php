<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_theme_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('journal_theme_id')->constrained()->cascadeOnDelete();
            
            // Menyimpan konteks role spesifik di jurnal ini ('manager' atau 'reviewer')
            $table->string('role_in_theme'); 
            
            $table->timestamps();
            
            // Mencegah duplikasi peran yang sama untuk user yang sama di satu jurnal
            $table->unique(['user_id', 'journal_theme_id', 'role_in_theme'], 'user_theme_role_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_theme_user');
    }
};
