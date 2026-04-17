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
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('keywords')->nullable()->after('abstract');
            $table->json('co_authors')->nullable()->after('author_id'); // Pakai JSON agar bisa muat banyak orang
            $table->foreignId('journal_issue_id')->nullable()->constrained('journal_issues')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('keywords');
            $table->dropColumn('co_authors');
            $table->dropForeign(['journal_issue_id']);
            $table->dropColumn('journal_issue_id');
        });
    }
};
