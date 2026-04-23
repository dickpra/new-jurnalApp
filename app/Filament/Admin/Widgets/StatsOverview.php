<?php

namespace App\Filament\Admin\Widgets;


use App\Models\User;
use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Muncul paling atas

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->description('Semua pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Pending Approval', User::where('is_approved', false)->count())
                ->description('User menunggu persetujuan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total Pengunjung', Visitor::count())
                ->description('Akumulasi visitor unik')
                ->descriptionIcon('heroicon-m-eye')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Contoh grafik kecil
                ->color('success'),
        ];
    }
}