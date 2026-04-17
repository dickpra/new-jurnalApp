<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Konfigurasi Pembayaran (Bisa diisi nanti oleh Pengelola)
            $table->decimal('author_fee_usd', 8, 2)->nullable();
            $table->decimal('listener_fee_usd', 8, 2)->nullable();
            $table->string('vat_number')->nullable();
            $table->text('org_address')->nullable();
            
            // Detail Bank
            $table->string('bank_name')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_owner_name')->nullable();
            $table->string('bank_city')->nullable();
            $table->text('account_owner_address')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_themes');
    }
};