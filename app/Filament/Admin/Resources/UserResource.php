<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $modelLabel = 'Pengguna (User)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Profil')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Akses & Lokasi')
                    ->schema([
                        Forms\Components\Select::make('country')
                            ->label('Negara Asal')
                            // Gunakan array_combine agar formatnya jadi ['Indonesia' => 'Indonesia']
                            ->options(array_combine(config('countries'), config('countries')))
                            ->searchable()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('country')
                    ->label('Negara')
                    ->searchable()
                    ->sortable(),

                // Tables\Columns\TextColumn::make('role')
                //     ->label('Role')
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state) {
                //         'super_admin' => 'danger',
                //         'manager' => 'warning',
                //         'reviewer' => 'info',
                //         'author' => 'success',
                //         default => 'gray',
                //     })
                //     ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'manager' => 'Manager',
                        'reviewer' => 'Reviewer',
                        'author' => 'Author',
                    ]),
                Tables\Filters\SelectFilter::make('country')
                    // Gunakan trik array_combine yang sama
                    ->options(array_combine(config('countries'), config('countries')))
                    ->searchable(),
            ])
            ->actions([
                // 1. TOMBOL APPROVE (Hanya muncul kalau belum di-approve)
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn ($record) => $record->is_approved) 
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_approved' => true]);
                        event(new \Illuminate\Auth\Events\Registered($record));
                        \Filament\Notifications\Notification::make()->success()->title('User Disetujui')->send();
                    }),

                // 2. TOMBOL SUSPEND / CABUT AKSES (Hanya muncul kalau SUDAH di-approve)
                Tables\Actions\Action::make('suspend')
                    ->label('Batalkan Akses')
                    ->icon('heroicon-o-no-symbol') // Ikon dilarang/blokir
                    ->color('warning')
                    ->hidden(fn ($record) => ! $record->is_approved) // Muncul kalau statusnya TRUE
                    ->requiresConfirmation()
                    ->modalHeading('Cabut Akses Pengguna')
                    ->modalDescription('Apakah Anda yakin? Pengguna ini tidak akan bisa login lagi ke sistem.')
                    ->action(function ($record) {
                        // Kembalikan status ke false
                        $record->update(['is_approved' => false]);
                        
                        \Filament\Notifications\Notification::make()
                            ->warning()
                            ->title('Akses Dicabut')
                            ->body('Pengguna kini dikembalikan ke status Pending.')
                            ->send();
                    }),

                // 3. TOMBOL REJECT (Hanya muncul kalau belum di-approve)
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->hidden(fn ($record) => $record->is_approved) 
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pengguna')
                    ->modalDescription('Data pendaftar akan dihapus dari sistem. Yakin?')
                    ->action(function ($record) {
                        $record->delete();
                        \Filament\Notifications\Notification::make()->danger()->title('User Ditolak')->send();
                    }),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}