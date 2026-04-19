<?php

namespace App\Filament\Manager\Widgets;

use App\Models\JournalTheme;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MyJournalsWidget extends BaseWidget
{
    // Bikin tabelnya membentang penuh (Full Width)
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Tarik HANYA jurnal di mana user ini menjadi manager
                auth()->user()->journalThemes()->wherePivot('role_in_theme', 'manager')->getQuery()
            )
            ->heading('Daftar Jurnal yang Anda Kelola')
            ->description('Gunakan kolom pencarian untuk menemukan jurnal dengan cepat.')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Jurnal')
                    ->searchable() // FITUR PENCARIAN AKTIF!
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('URL Slug')
                    ->searchable(),

                Tables\Columns\TextColumn::make('accreditation_status')
                    ->label('Status')
                    ->badge(),
            ])
            ->actions([
                Tables\Actions\Action::make('switch_journal')
                    ->label('Masuk ke Jurnal Ini')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('indigo')
                    // Fungsi untuk melompat ke dashboard jurnal yang diklik
                    ->url(fn (JournalTheme $record): string => url('/manager/theme/' . $record->slug)),
            ]);
    }
}