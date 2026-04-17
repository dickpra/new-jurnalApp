<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'is_super_admin', // Tambahkan boolean ini di migration users jika belum
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
    ];

    // Relasi ke Tema Jurnal
    public function journalThemes(): BelongsToMany
    {
        return $this->belongsToMany(JournalTheme::class)
                    ->withPivot('role_in_theme')
                    ->withTimestamps();
    }

    // --- Syarat Wajib Filament Multi-Tenancy ---

    public function getTenants(Panel $panel): Collection
    {
        // Jika user adalah super admin, dia tidak punya tenant spesifik (akses panel global)
        // Jika bukan super admin, ambil semua jurnal di mana dia terdaftar sebagai pengelola/reviewer
        return $this->journalThemes; 
    }

    public function canAccessTenant(Model $tenant): bool
    {
        // Validasi apakah user ini benar-benar punya akses ke jurnal ($tenant) yang dituju
        return $this->journalThemes()->whereKey($tenant)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Verifikasi akses panel. Misalnya panel 'admin' hanya untuk super_admin
        if ($panel->getId() === 'admin') {
            return $this->is_super_admin;
        }

        // Panel 'manager' mewajibkan verifikasi email terlebih dahulu
        return $this->hasVerifiedEmail();
    }
}