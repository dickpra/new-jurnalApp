<?php

namespace App\Filament\Admin\Widgets;


use App\Models\Visitor;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VisitorChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Kunjungan (7 Hari Terakhir)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Menghitung data kunjungan per hari
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data[] = Visitor::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengunjung',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                ],
            ],
            'labels' => ['6 hari lalu', '5 hari lalu', '4 hari lalu', '3 hari lalu', '2 hari lalu', 'Kemarin', 'Hari Ini'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}