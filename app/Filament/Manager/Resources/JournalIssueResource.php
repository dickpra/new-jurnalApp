<?php

namespace App\Filament\Manager\Resources;

use App\Filament\Manager\Resources\JournalIssueResource\Pages;
use App\Filament\Manager\Resources\JournalIssueResource\RelationManagers;
use App\Models\JournalIssue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;
use App\Filament\Manager\Resources\JournalIssueResource\RelationManagers\SubmissionsRelationManager;

class JournalIssueResource extends Resource
{
    protected static ?string $model = JournalIssue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $isScopedToTenant = false;
    protected static ?string $navigationLabel = 'Journal Issues Volume';

    public static function getEloquentQuery(): Builder
    {
        // FIX LOGIKA FATAL: Sebelumnya kamu pakai 'id', padahal seharusnya 'journal_theme_id'
        // Kalau pakai 'id', Manager tidak akan pernah melihat Volume yang dia buat!
        return parent::getEloquentQuery()->where('journal_theme_id', Filament::getTenant()->id);
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan baris ini:
            RelationManagers\SubmissionsRelationManager::class,
        ];
    }


    // app/Filament/Manager/Resources/JournalIssueResource.php
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('volume')->label('Volume (Contoh: Vol. 1)')->required(),
            Forms\Components\TextInput::make('issue')->label('Nomor Issue (Contoh: No. 2)')->required(),
            Forms\Components\TextInput::make('year')->numeric()->required(),
            Forms\Components\Toggle::make('is_active')->label('Status Aktif')->default(true),
            Forms\Components\FileUpload::make('cover_image')
            ->label('Sampul Volume (JPG/PNG)')
            ->image()
            ->directory('journal-covers') // Folder simpan
            ->columnSpanFull(),
            Forms\Components\Hidden::make('journal_theme_id')->default(fn () => Filament::getTenant()->id),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // FIX TAMPILAN: Sekarang kolomnya ada isinya!
                Tables\Columns\TextColumn::make('volume')
                    ->label('Volume')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('issue')
                    ->label('Issue/Nomor')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                
                // Pakai ToggleColumn agar Manager bisa on/off volume langsung dari tabel luar
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif?'),
                
                // Menampilkan jumlah naskah yang ada di dalam volume ini
                Tables\Columns\TextColumn::make('submissions_count')
                    ->counts('submissions')
                    ->label('Total Naskah')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournalIssues::route('/'),
            'create' => Pages\CreateJournalIssue::route('/create'),
            'edit' => Pages\EditJournalIssue::route('/{record}/edit'),
        ];
    }
}
