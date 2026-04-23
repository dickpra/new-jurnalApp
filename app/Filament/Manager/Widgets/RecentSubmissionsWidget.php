<?php

namespace App\Filament\Manager\Widgets;

use App\Models\Submission; // Menggunakan model Submission
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentSubmissionsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full'; 
    protected static ?string $heading = 'Antrean Naskah Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Menampilkan naskah terbaru yang masuk
                Submission::query()->latest()
            ) 
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Naskah')
                    ->limit(60)
                    ->searchable()
                    ->weight('bold'),
                
                // Asumsi kamu punya relasi belongsTo('author') di model Submission
                Tables\Columns\TextColumn::make('author.name') 
                    ->label('Author')
                    ->searchable()
                    ->placeholder('Unknown'),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Naskah')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'pending' => 'warning',
                        'in_review' => 'info',
                        'revision' => 'danger',
                        'accepted' => 'success',
                        'published' => 'success',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn (?string $state): string => match (strtolower($state ?? '')) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Submit')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                // Karena kita belum tahu nama pasti route resource Submission di panel Manager,
                // saya arahkan menggunakan URL statis / id sementara agar tidak error "Route not found"
                Tables\Actions\Action::make('view')
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    // Cukup arahkan ke record view/edit
                    ->url(fn (Submission $record): string => url('/manager/submissions/' . $record->id . '/edit'))
            ])
            ->paginated([5]); 
    }
}