<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_theme_id')->constrained()->cascadeOnDelete();
            
            // Siapa author yang mengirim?
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            
            $table->string('title');
            $table->text('abstract');
            $table->string('manuscript_file'); // Path file di private storage
            
            // Kita simpan value dari Enum di sini
            $table->string('status')->default('pending');
            
            // Putaran review (Round 1, 2, atau 3)
            $table->integer('current_round')->default(1); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
