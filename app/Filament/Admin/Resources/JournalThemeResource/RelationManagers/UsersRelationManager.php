<?php

namespace App\Filament\Admin\Resources\JournalThemeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Anggota Pengurus Jurnal';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kita kosongkan form default, karena kita akan pakai AttachAction di bawah
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role_in_theme')
                    ->label('Peran di Jurnal Ini')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'manager' => 'Pengelola / Manager',
                        'reviewer' => 'Reviewer',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'manager' => 'success',
                        'reviewer' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // INI KUNCINYA: Fitur Attach (Menambahkan user yang sudah ada ke jurnal ini)
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(), // Menampilkan dropdown pilihan User
                        // Menambahkan form input untuk mengisi tabel pivot 'role_in_theme'
                        Forms\Components\Select::make('role_in_theme')
                            ->label('Pilih Peran')
                            ->options([
                                'manager' => 'Pengelola Jurnal',
                                // 'reviewer' => 'Reviewer',
                            ])
                            ->required(),
                    ]),
            ])
            ->actions([
                // Menghapus hak akses user dari jurnal ini (Detach)
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}