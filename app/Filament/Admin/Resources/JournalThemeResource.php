<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\JournalThemeResource\Pages;
use App\Models\JournalTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\JournalThemeResource\RelationManagers;
use Filament\Forms\Components\Tabs;

class JournalThemeResource extends Resource
{
    protected static ?string $model = JournalTheme::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Manajemen Tema Jurnal';
    protected static ?string $modelLabel = 'Tema Jurnal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Pengaturan Lengkap Tema Jurnal')
                    ->tabs([
                        // TAB 1: INFO DASAR (Dengan Auto-Slug Super Admin)
                        Tabs\Tab::make('Info Dasar')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Tema / Jurnal')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(JournalTheme::class, 'slug', ignoreRecord: true),

                                Forms\Components\FileUpload::make('journal_logo')
                                    ->label('Logo Jurnal')
                                    ->image()
                                    ->directory('journals'),
                                    
                                Forms\Components\FileUpload::make('default_cover_image')
                                    ->label('Cover Default (Sampul Bawaan)')
                                    ->image()
                                    ->directory('journals'),
                                    
                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Singkat')
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // TAB 2: IDENTITAS AKADEMIK
                        Tabs\Tab::make('Identitas Akademik')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Forms\Components\TextInput::make('publisher')
                                    ->label('Penerbit / Institusi')
                                    ->placeholder('Misal: Universitas Yudharta'),
                                Forms\Components\TextInput::make('accreditation_status')
                                    ->label('Status Akreditasi')
                                    ->placeholder('Misal: SINTA 2'),
                                Forms\Components\TextInput::make('e_issn')
                                    ->label('e-ISSN (Online)'),
                                Forms\Components\TextInput::make('p_issn')
                                    ->label('p-ISSN (Cetak)'),
                                Forms\Components\TextInput::make('publication_frequency')
                                    ->label('Frekuensi Terbit')
                                    ->placeholder('Misal: 2 Kali Setahun'),
                                
                                Forms\Components\RichEditor::make('focus_scope')
                                    ->label('Focus and Scope')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('peer_review_process')
                                    ->label('Peer Review Process')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // TAB 3: KONTAK REDAKSI
                        Tabs\Tab::make('Kontak Redaksi')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Forms\Components\TextInput::make('principal_contact_name')
                                    ->label('Nama Kontak Utama (Chief Editor)'),
                                Forms\Components\TextInput::make('support_email')
                                    ->label('Email Resmi Jurnal')
                                    ->email(),
                                Forms\Components\Textarea::make('mailing_address')
                                    ->label('Alamat Kantor Redaksi')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // TAB 4: KEUANGAN & BANK
                        Tabs\Tab::make('Finansial & Bank')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Forms\Components\Section::make('Biaya Registrasi')
                                    ->description('Kosongkan jika jurnal ini gratis (Open Access tanpa APC)')
                                    ->schema([
                                        Forms\Components\TextInput::make('author_fee_usd')
                                            ->label('Biaya Author (USD)')
                                            ->numeric()
                                            ->prefix('$'),
                                        Forms\Components\TextInput::make('listener_fee_usd')
                                            ->label('Biaya Tambahan / Fast-Track (USD)')
                                            ->numeric()
                                            ->prefix('$'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Pajak & Organisasi')
                                    ->schema([
                                        Forms\Components\TextInput::make('vat_number')
                                            ->label('Nomor NPWP / VAT'),
                                        Forms\Components\Textarea::make('org_address')
                                            ->label('Alamat Penagihan Organisasi')
                                            ->rows(2),
                                    ]),

                                Forms\Components\Section::make('Informasi Rekening Bank')
                                    ->schema([
                                        Forms\Components\TextInput::make('bank_name')
                                            ->label('Nama Bank'),
                                        Forms\Components\TextInput::make('swift_code')
                                            ->label('Kode SWIFT / BIC'),
                                        Forms\Components\TextInput::make('account_number')
                                            ->label('Nomor Rekening'),
                                        Forms\Components\TextInput::make('account_owner_name')
                                            ->label('Nama Pemilik Rekening'),
                                        Forms\Components\TextInput::make('bank_city')
                                            ->label('Kota Bank'),
                                        Forms\Components\Textarea::make('account_owner_address')
                                            ->label('Alamat Pemilik Rekening')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tema')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('accreditation_status')
                    ->label('Akreditasi')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            // Relasi ini SANGAT PENTING untuk mendaftarkan Manager ke jurnal
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournalThemes::route('/'),
            'create' => Pages\CreateJournalTheme::route('/create'),
            'edit' => Pages\EditJournalTheme::route('/{record}/edit'),
        ];
    }
}