<?php

namespace App\Providers\Filament;

use App\Models\JournalTheme;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\MenuItem;


class ManagerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('manager')
            ->path('manager')
            ->login() // Menggunakan halaman login bawaan Filament

            // ==========================================
            // JAWABAN PERTANYAAN 1: Pintu Pulang ke Author
            // ==========================================
            ->userMenuItems([
                MenuItem::make()
                    ->label('Kembali ke Author Workspace') // Teks yang muncul
                    ->url(fn (): string => route('author.dashboard')) // Arahkan ke rute Breeze kamu
                    ->icon('heroicon-o-arrow-uturn-left') // Icon panah putar balik
                    ->sort(1), // Muncul paling atas di dropdown profil
            ])

            // ==========================================
            // JAWABAN PERTANYAAN 2: Percantik Tenant Switcher
            // ==========================================
            ->tenantMenu(true) // Memastikan menu dropdown antar jurnal aktif

            ->colors([
                'primary' => Color::Indigo, // Bisa disesuaikan
            ])
            ->discoverResources(in: app_path('Filament/Manager/Resources'), for: 'App\\Filament\\Manager\\Resources')
            ->discoverPages(in: app_path('Filament/Manager/Pages'), for: 'App\\Filament\\Manager\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Manager/Widgets'), for: 'App\\Filament\\Manager\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                \App\Filament\Manager\Widgets\MyJournalsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // --- INI KUNCI MULTI-TENANCY NYA ---
            ->tenant(JournalTheme::class, slugAttribute: 'slug')
            ->tenantRoutePrefix('theme'); // Akan membuat URL jadi /manager/theme/{slug}
    }
}