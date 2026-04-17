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


class JournalIssueResource extends Resource
{
    protected static ?string $model = JournalIssue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $isScopedToTenant = false;
    
    public static function getEloquentQuery(): Builder
        {
            return parent::getEloquentQuery()->where('id', Filament::getTenant()->id);
        }


    // app/Filament/Manager/Resources/JournalIssueResource.php
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('volume')->label('Volume (Contoh: Vol. 1)')->required(),
            Forms\Components\TextInput::make('issue')->label('Nomor Issue (Contoh: No. 2)')->required(),
            Forms\Components\TextInput::make('year')->numeric()->required(),
            Forms\Components\Toggle::make('is_active')->label('Status Aktif')->default(true),
            Forms\Components\Hidden::make('journal_theme_id')->default(fn () => Filament::getTenant()->id),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
