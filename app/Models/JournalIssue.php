<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalIssue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function journalTheme()
    {
        return $this->belongsTo(JournalTheme::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
