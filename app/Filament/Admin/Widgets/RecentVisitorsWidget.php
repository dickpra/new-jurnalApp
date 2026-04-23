<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Visitor;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentVisitorsWidget extends BaseWidget
{
    protected static ?int $sort = 3; // Urutan widget di dashboard
    protected int | string | array $columnSpan = 'full'; // Lebar penuh
    protected static ?string $heading = 'Pelacakan Pengunjung Terakhir (IP & Negara)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visitor::query()->latest() // Ambil data paling baru
            )
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->fontFamily('mono') // Font ala hacker/kode
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label('Negara')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Indonesia' => 'success',
                        'Unknown' => 'danger',
                        default => 'info',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Kunjungan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->paginated([5, 10, 25]); // Menampilkan 5 baris per halaman
    }
}