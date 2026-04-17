<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'co_authors' => 'array',
        'status' => SubmissionStatus::class, // <-- MAGIC-nya di sini
    ];

    public function journalTheme(): BelongsTo
    {
        return $this->belongsTo(JournalTheme::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Relasi ke Volume/Issue
    public function journalIssue()
    {
        return $this->belongsTo(JournalIssue::class);
    }
}