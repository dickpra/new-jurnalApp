<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JournalTheme extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke User
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role_in_theme')
                    ->withTimestamps();
    }
}