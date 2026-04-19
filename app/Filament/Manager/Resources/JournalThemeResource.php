<?php

namespace App\Filament\Manager\Resources;

use App\Models\JournalTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class JournalThemeResource extends Resource
{
    protected static ?string $model = JournalTheme::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    // Label diganti agar lebih mencerminkan Jurnal
    protected static ?string $navigationLabel = 'Journal Settings';
    protected static ?string $pluralLabel = 'Journal Settings';

    // 1. KUNCI UTAMA FIX ERROR: Matikan otomatisasi tenant untuk resource ini
    protected static bool $isScopedToTenant = false;

    public static function canCreate(): bool
    {
        return false;
    }
    
    public static function canDelete($record): bool
    {
        return false;
    }

    // 2. KUNCI KEDUA: Paksa agar Manager cuma bisa melihat jurnal miliknya sendiri
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', Filament::getTenant()->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        // TAB 1: GENERAL INFO (Ditambah Logo & Cover)
                        Tabs\Tab::make('General Information')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Journal Name'),
                                TextInput::make('slug')
                                    ->disabled() // Slug biasanya diatur Super Admin
                                    ->label('URL Slug'),
                                FileUpload::make('journal_logo')
                                    ->label('Journal Logo')
                                    ->image()
                                    ->directory('journals'),
                                FileUpload::make('default_cover_image')
                                    ->label('Default Cover Image')
                                    ->image()
                                    ->directory('journals'),
                                RichEditor::make('description')
                                    ->label('Short Description')
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // TAB 2: ACADEMIC IDENTITY & POLICIES (Baru)
                        Tabs\Tab::make('Academic Identity')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                TextInput::make('publisher')
                                    ->label('Publisher / Institution')
                                    ->placeholder('e.g., Faculty of Agriculture, Yudharta University'),
                                TextInput::make('accreditation_status')
                                    ->label('Accreditation Status')
                                    ->placeholder('e.g., SINTA 2, Scopus Q3'),
                                TextInput::make('e_issn')
                                    ->label('e-ISSN (Online)'),
                                TextInput::make('p_issn')
                                    ->label('p-ISSN (Print)'),
                                TextInput::make('publication_frequency')
                                    ->label('Publication Frequency')
                                    ->placeholder('e.g., Biannually (June & December)'),
                                
                                RichEditor::make('focus_scope')
                                    ->label('Focus and Scope')
                                    ->columnSpanFull(),
                                Textarea::make('peer_review_process')
                                    ->label('Peer Review Process')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // TAB 3: EDITORIAL CONTACT (Baru)
                        Tabs\Tab::make('Editorial Contact')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                TextInput::make('principal_contact_name')
                                    ->label('Principal Contact (Chief Editor)'),
                                TextInput::make('support_email')
                                    ->label('Official Support Email')
                                    ->email(),
                                Textarea::make('mailing_address')
                                    ->label('Editorial Office Address')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // TAB 4: PAYMENT & BANK (Kodingan Asli Milikmu Dipertahankan 100%)
                        Tabs\Tab::make('Payment & Bank')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Section::make('Registration Fees')
                                    ->description('Leave blank if free')
                                    ->schema([
                                        TextInput::make('author_fee_usd')
                                            ->label('Author (Presenter) Fee (USD)')
                                            ->numeric()
                                            ->prefix('$'),
                                        TextInput::make('listener_fee_usd')
                                            ->label('Participant (Listener) Fee (USD)')
                                            ->numeric()
                                            ->prefix('$'),
                                    ])->columns(2),

                                Section::make('Tax & Organization')
                                    ->schema([
                                        TextInput::make('vat_number')
                                            ->label('VAT Number (NPWP)'),
                                        Textarea::make('org_address')
                                            ->label('Organization Postal Address')
                                            ->rows(2),
                                    ]),

                                Section::make('Bank Account Information')
                                    ->description('Complete this data for international and local payments')
                                    ->schema([
                                        TextInput::make('bank_name')
                                            ->label('Bank Name')
                                            ->required(),
                                        TextInput::make('swift_code')
                                            ->label('SWIFT / BIC Code')
                                            ->placeholder('Leave blank for local transfers'),
                                        TextInput::make('account_number')
                                            ->label('Account Number')
                                            ->required(),
                                        TextInput::make('account_owner_name')
                                            ->label('Name of Account Owner')
                                            ->required(),
                                        TextInput::make('bank_city')
                                            ->label('Bank City'),
                                        Textarea::make('account_owner_address')
                                            ->label("Account Owner's Registered Address")
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
                    ->label('Journal Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('accreditation_status')
                    ->label('Status')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('author_fee_usd')
                    ->label('Author Fee')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Bank'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Update Settings'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Manager\Resources\JournalThemeResource\Pages\ListJournalThemes::route('/'),
            'edit' => \App\Filament\Manager\Resources\JournalThemeResource\Pages\EditJournalTheme::route('/{record}/edit'),
        ];
    }
}