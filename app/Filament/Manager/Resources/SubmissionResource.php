<?php

namespace App\Filament\Manager\Resources;

use App\Filament\Manager\Resources\SubmissionResource\Pages;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Storage;
use App\Filament\Manager\Resources\SubmissionResource\RelationManagers;
use App\Enums\SubmissionStatus;
use Filament\Forms\Components\Placeholder;
use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Submissions';
    protected static ?string $pluralLabel = 'Daftar Naskah';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Naskah')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Placeholder::make('status')
                            ->label('Status Naskah')
                            ->content(fn ($record) => new HtmlString('<strong style="font-size: 1.1rem; color: #1c1917;">' . ($record?->status->getLabel() ?? '-') . '</strong>'))
                            ->columnSpanFull(),
                        Placeholder::make('title_view')
                            ->label('Judul Naskah')
                            ->content(fn ($record) => new HtmlString('<strong style="font-size: 1.1rem; color: #1c1917;">' . ($record?->title ?? '-') . '</strong>'))
                            ->columnSpanFull(),

                        Placeholder::make('abstract_view')
                            ->label('Abstrak')
                            ->content(fn ($record) => new HtmlString('<div style="text-align: justify; color: #44403c; padding: 1rem; background-color: #f5f5f4; border-radius: 0.5rem; border-left: 4px solid #1c1917;">' . nl2br(e($record?->abstract ?? '-')) . '</div>'))
                            ->columnSpanFull(),

                        Placeholder::make('keywords_view')
                            ->label('Keywords')
                            ->content(function ($record) {
                                if (!$record?->keywords) return '-';
                                // Pecah keyword dan jadikan badge kecil
                                $keywords = explode(',', $record->keywords);
                                $badges = collect($keywords)->map(fn($k) => '<span style="background-color: #e7e5e4; color: #1c1917; padding: 2px 8px; border-radius: 999px; font-size: 0.75rem; margin-right: 4px; display: inline-block;">' . trim($k) . '</span>')->implode('');
                                return new HtmlString($badges);
                            }),

                        Placeholder::make('theme_info')
                            ->label('Tema Jurnal')
                            ->content(fn ($record) => new HtmlString('<span style="color: #0369a1; font-weight: bold;">📚 ' . ($record?->journalTheme?->name ?? 'Tidak diketahui') . '</span>')),

                        // DAFTAR PENULIS DENGAN BADGE WARNA-WARNI
                        Placeholder::make('co_authors_view')
                            ->label('Daftar Penulis (Authors)')
                            ->content(function ($record) {
                                if (empty($record?->co_authors)) return '-';
                                
                                $authors = collect($record->co_authors)->map(function($a, $index) {
                                    if ($index === 0) {
                                        // Badge Penulis 1 (Hitam Elegan)
                                        return "<span style='background-color: #1c1917; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.70rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;'>Penulis Utama</span> <strong style='font-size: 1rem; margin-left: 8px;'>" . e($a['name']) . "</strong> <span style='color: #78716c; font-size: 0.85rem;'>(" . e($a['email']) . ")</span>";
                                    } else {
                                        // Badge Co-Author (Abu-abu)
                                        return "<span style='background-color: #e7e5e4; color: #57534e; padding: 2px 8px; border-radius: 4px; font-size: 0.70rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;'>Co-Author " . $index . "</span> <span style='font-size: 0.95rem; margin-left: 8px;'>" . e($a['name']) . "</span> <span style='color: #78716c; font-size: 0.85rem;'>(" . e($a['email']) . ")</span>";
                                    }
                                })->implode('<br><br>');
                                
                                return new HtmlString('<div style="padding: 1rem; border: 1px solid #e7e5e4; border-radius: 0.5rem;">' . $authors . '</div>');
                            })
                            ->columnSpanFull(),

                        Forms\Components\Select::make('journal_issue_id')
                            ->label('Publish to Issue / Volume')
                            ->relationship(
                                'journalIssue', 
                                'volume', 
                                fn ($query) => $query->where('journal_theme_id', Filament::getTenant()->id)
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->volume} {$record->issue} ({$record->year})")
                            ->disabled(fn ($record) => !in_array($record?->status?->value, ['accepted', 'paid'])) 
                            ->helperText('Pilih naskah ini akan diterbitkan di volume berapa.'),
                    ])->columns(2),

                Section::make('Kontrol Manajer')
                    ->icon('heroicon-o-cog-8-tooth')
                    ->schema([
                        Select::make('status')
                            ->label('Status Naskah')
                            ->options(SubmissionStatus::class)
                            ->disabled() 
                            ->dehydrated(false) 
                            ->native(false),

                        Placeholder::make('current_round_view')
                            ->label('Putaran Review')
                            ->content(fn ($record) => new HtmlString('<span style="background-color: #fef08a; color: #854d0e; padding: 4px 12px; border-radius: 999px; font-weight: bold;">🔄 Ronde ' . ($record?->current_round ?? 1) . '</span>')),

                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'pending_verification' => 'Waiting Verification',
                                'paid' => 'Paid',
                            ])
                            ->disabled(fn ($record) => !in_array($record?->status?->value, ['accepted', 'paid']))
                            ->native(false)
                            ->afterStateUpdated(function ($state, $record, $set) {
                                if ($state === 'paid' && $record) {
                                    $record->update(['status' => 'paid']);
                                } elseif ($state === 'unpaid' && $record) {
                                    $record->update(['status' => 'accepted']);
                                }
                                return redirect(request()->header('Referer'));
                            }),

                        Forms\Components\FileUpload::make('loa_file')
                            ->label('Custom LOA File (Opsional)')
                            ->directory('loas')
                            ->disk('local') 
                            ->acceptedFileTypes(['application/pdf'])
                            ->helperText('Upload PDF khusus jika tidak ingin menggunakan LOA Otomatis.')
                            ->disabled(fn ($record) => $record?->status?->value !== 'accepted'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Naskah & Penulis')
                    ->wrap()
                    ->weight('bold')
                    ->description(function ($record) {
                        // Logika mengambil Penulis 1 dari array inputan (co_authors)
                        if (is_array($record->co_authors) && count($record->co_authors) > 0) {
                            $firstAuthor = $record->co_authors[0]['name'];
                            $etAl = count($record->co_authors) > 1 ? ' et al.' : '';
                            return "✍️ " . $firstAuthor . $etAl;
                        }
                        return "✍️ Belum ada data penulis";
                    }),
                
                TextColumn::make('reviews_count')
                    ->label('Reviewers')
                    ->counts('reviews')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-users'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                    
                TextColumn::make('current_round')
                    ->label('Round')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-arrow-path')
                    ->formatStateUsing(fn ($state) => "Ronde " . $state),
            ])
            ->actions([
                // 1. TOMBOL VIEW/DOWNLOAD FILE
                Action::make('download')
                    ->label('Download Naskah')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->action(function (Submission $record) {
                        if (!$record->manuscript_file || !Storage::disk('local')->exists($record->manuscript_file)) {
                            \Filament\Notifications\Notification::make()
                                ->title('File Tidak Ditemukan!')
                                ->body('File naskah mungkin belum diupload atau hilang dari server.')
                                ->danger()
                                ->send();
                            return;
                        }
                        return Storage::disk('local')->download($record->manuscript_file);
                    }),
                
                // 2. TOMBOL PUBLISH
                Action::make('publish_paper')
                    ->label('Publish Paper')
                    ->icon('heroicon-o-megaphone')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status->value === 'paid' && $record->payment_status === 'paid')
                    ->form([
                        Forms\Components\Select::make('journal_issue_id')
                            ->label('Select Volume / Issue')
                            ->options(fn () => \App\Models\JournalIssue::where('journal_theme_id', \Filament\Facades\Filament::getTenant()->id)
                                ->get()
                                ->mapWithKeys(fn ($issue) => [$issue->id => "{$issue->volume} No. {$issue->issue} ({$issue->year})"]))
                            ->required(),
                        Forms\Components\TextInput::make('doi')
                            ->label('Digital Object Identifier (DOI)')
                            ->placeholder('e.g., 10.1234/agromix.v1i1.123'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => \App\Enums\SubmissionStatus::PUBLISHED,
                            'journal_issue_id' => $data['journal_issue_id'],
                            'doi' => $data['doi'],
                        ]);

                        $mailable = new \App\Mail\PaperPublishedNotification($record, "Congratulations! Your research is now officially published.");
                        \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                        \Filament\Notifications\Notification::make()->title('Paper Published Successfully!')->success()->send();
                    }),

                // 3. TOMBOL REQUEST REVISION
                Action::make('request_revision')
                    ->label('Request Revision')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Submission $record) => $record->status === SubmissionStatus::UNDER_REVIEW)
                    ->mountUsing(fn (Forms\ComponentContainer $form, Submission $record) => $form->fill([
                        'revision_notes' => $record->reviews->pluck('notes_for_author')->filter()->implode("\n\n---\n\n"),
                    ]))
                    ->form([
                        Forms\Components\Textarea::make('revision_notes')
                            ->label('Revision Instructions for Author')
                            ->rows(8)
                            ->required(),
                    ])
                    ->action(function (Submission $record, array $data): void {
                        $record->update([
                            'status' => SubmissionStatus::REVISION_REQUIRED,
                            'revision_notes' => $data['revision_notes'],
                        ]);
                        
                        \Filament\Notifications\Notification::make()->title('Revision request sent')->success()->send();
                    }),

                // 4. CEK STRUK & VERIFIKASI PEMBAYARAN
                Action::make('download_payment')
                    ->label('Cek Struk')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('info')
                    ->visible(fn (Submission $record) => $record->payment_proof !== null)
                    ->action(function (Submission $record) {
                        if (!Storage::disk('local')->exists($record->payment_proof)) {
                            \Filament\Notifications\Notification::make()->title('Struk hilang')->danger()->send();
                            return;
                        }
                        return Storage::disk('local')->download($record->payment_proof);
                    }),

                Action::make('confirm_payment')
                    ->label('Verify Payment')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status->value === 'accepted' && in_array($record->payment_status, ['unpaid', 'pending_verification']))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['payment_status' => 'paid']);

                        $mailable = new \App\Mail\PaymentConfirmedNotification($record, "Thank you for completing the registration payment.");
                        \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                        \Filament\Notifications\Notification::make()->title('Payment Verified')->success()->send();
                    }),

                ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubmissions::route('/'),
            'edit' => Pages\EditSubmission::route('/{record}/edit'),
        ];
    }
}