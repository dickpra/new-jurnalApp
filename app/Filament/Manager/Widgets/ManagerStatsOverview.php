<?php

namespace App\Filament\Manager\Widgets;

use App\Models\Submission; // Menggunakan model Submission
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ManagerStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        try {
            $totalSubmissions = Submission::count();
            // Asumsi status naskah baru adalah 'pending' atau 'submitted'
            $pendingReview = Submission::where('status', 'pending')->count(); 
            // Asumsi naskah yang sudah diterima/publish
            $accepted = Submission::where('status', 'accepted')->count(); 
        } catch (\Exception $e) {
            $totalSubmissions = 0;
            $pendingReview = 0;
            $accepted = 0;
        }

        return [
            Stat::make('Total Naskah Masuk', $totalSubmissions)
                ->description('Seluruh artikel yang pernah disubmit')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary'),

            Stat::make('Menunggu Review / Tindakan', $pendingReview)
                ->description('Naskah berstatus Pending')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'animate-pulse', // Berkedip tanda butuh perhatian
                ]),

            Stat::make('Naskah Diterima (Accepted)', $accepted)
                ->description('Naskah yang lolos tahap review')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}