<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            
            // Siapa reviewer yang ditugaskan?
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            
            // Ronde ke-berapa dia mereview?
            $table->integer('round');
            
            // Hasil review (dari Enum), nullable karena reviewer belum tentu langsung ngisi
            $table->string('recommendation')->nullable(); 
            
            $table->text('notes_for_author')->nullable();
            $table->text('notes_for_manager')->nullable(); // Catatan rahasia ke pengelola
            
            $table->boolean('is_completed')->default(false);
            
            $table->timestamps();
            
            // Satu reviewer hanya boleh ada 1 record per ronde untuk naskah yang sama
            $table->unique(['submission_id', 'reviewer_id', 'round']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};