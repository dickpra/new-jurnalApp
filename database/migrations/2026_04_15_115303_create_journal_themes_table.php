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
            
            // 1. IDENTITAS RESMI JURNAL (Baru)
            $table->string('e_issn')->nullable();
            $table->string('p_issn')->nullable();
            $table->string('publisher')->nullable();
            $table->string('accreditation_status')->nullable();
            
            // 2. RUANG LINGKUP & KEBIJAKAN (Baru)
            $table->text('focus_scope')->nullable();
            $table->text('peer_review_process')->nullable();
            $table->string('publication_frequency')->nullable();
            
            // 3. KONTAK REDAKSI (Baru)
            $table->string('principal_contact_name')->nullable();
            $table->string('support_email')->nullable();
            $table->text('mailing_address')->nullable();
            
            // 4. VISUAL & BRANDING (Baru)
            $table->string('journal_logo')->nullable();
            $table->string('default_cover_image')->nullable();
            
            // 5. Konfigurasi Pembayaran & Bank (Bawaan Lama)
            $table->decimal('author_fee_usd', 8, 2)->nullable();
            $table->decimal('listener_fee_usd', 8, 2)->nullable();
            $table->string('vat_number')->nullable();
            $table->text('org_address')->nullable();
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