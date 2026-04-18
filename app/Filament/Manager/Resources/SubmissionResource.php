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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Storage;
use App\Filament\Manager\Resources\SubmissionResource\RelationManagers;
use App\Enums\SubmissionStatus; // Pastikan Enum ini sudah ada ya!
use Filament\Forms\Components\Placeholder;
use Filament\Facades\Filament;


class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Naskah Masuk';
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
                    ->schema([
                        // Judul & Abstrak tetap pakai input tapi disabled, 
                        // karena teksnya bisa panjang dan butuh ruang textarea
                        TextInput::make('title')
                            ->label('Judul Naskah')
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                        // Tampilkan Keywords
                    Forms\Components\TextInput::make('keywords')
                        ->label('Keywords')
                        ->disabled(),

                    // Tampilkan Co-Authors menggunakan fitur Repeater Filament yang keren
                    Forms\Components\Repeater::make('co_authors')
                        ->label('Author')
                        ->schema([
                            Forms\Components\TextInput::make('name')->label('Nama'),
                            Forms\Components\TextInput::make('email')->label('Email'),
                        ])
                        ->disabled()
                        ->columnSpanFull(),

                    // Pilihan untuk memasukkan naskah ini ke Volume berapa
                    Forms\Components\Select::make('journal_issue_id')
                        ->label('Publish to Issue / Volume')
                        ->relationship(
                            'journalIssue', 
                            'volume', 
                            fn ($query) => $query->where('journal_theme_id', Filament::getTenant()->id)
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->volume} {$record->issue} ({$record->year})")
                        ->disabled(fn ($record) => $record?->status?->value !== 'accepted' && $record?->status?->value !== 'paid') // Cuma bisa diisi kalau naskah Diterima
                        ->helperText('Pilih naskah ini akan diterbitkan di volume berapa.'),

                        Textarea::make('abstract')
                            ->label('Abstrak')
                            ->rows(6)
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),

                        // --- SOLUSI UI & BUG FIX NYA DI SINI ---
                        // Menggunakan Placeholder: Tampilan bersih, data pasti muncul, dan tidak akan di-save
                        Placeholder::make('author_info')
                            ->label('Penulis Utama')
                            ->content(fn ($record) => $record?->author?->name ?? 'Tidak diketahui'),

                        Placeholder::make('theme_info')
                            ->label('Tema Jurnal')
                            ->content(fn ($record) => $record?->journalTheme?->name ?? 'Tidak diketahui'),
                    ])->columns(2),

                Section::make('Kontrol Manajer')
                    ->schema([
                        Select::make('status')
                            ->label('Status Naskah')
                            ->options(SubmissionStatus::class)
                            ->disabled() // KUNCI! Admin gak bisa ganti status dari sini
                            ->dehydrated(false) // Cegah error saat disave
                            ->native(false),

                        TextInput::make('current_round')
                            ->label('Putaran Review Ke-')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),

                    ])->columns(2),

                    Select::make('payment_status')
                        ->label('Status Pembayaran')
                        ->options([
                            'unpaid' => 'Belum Bayar (Unpaid)',
                            'pending_verification' => 'Menunggu Verifikasi',
                            'paid' => 'Lunas (Paid)',
                        ])
                        // Hanya bisa diubah jika status naskah sudah 'accepted'
                        ->disabled(fn ($record) => $record?->status?->value !== 'accepted' && $record?->status?->value !== 'paid')
                        ->native(false)
                        ->afterStateUpdated(function ($state, $record, $set) {
                            // Jika dipilih 'paid', langsung update ke database
                            if ($state === 'paid' && $record) {
                                $record->update([
                                    'status' => 'paid',
                                ]);
                            }if ($state === 'unpaid' && $record) {
                                $record->update([
                                    'status' => 'accepted',
                                ]);
                            }
                            return redirect(request()->header('Referer'));
                        }),

                Forms\Components\FileUpload::make('loa_file')
                            ->label('Custom LOA File (Opsional)')
                            ->directory('loas')
                            ->disk('local') // TAMBAHKAN BARIS INI
                            ->acceptedFileTypes(['application/pdf'])
                            ->helperText('Upload PDF khusus jika tidak ingin menggunakan LOA Otomatis dari sistem.')
                            ->disabled(fn ($record) => $record?->status?->value !== 'accepted'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Journal Title')
                    ->wrap()
                    ->description(fn ($record) => "Author: " . ($record->author->name ?? 'Unknown')),
                
                TextColumn::make('reviews_count')
                    ->label('Reviewers')
                    ->counts('reviews')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(), // Filament otomatis ambil warna dari Enum SubmissionStatus

                    
                TextColumn::make('current_round')
                    ->label('Round')
                    ->formatStateUsing(fn ($state) => "Round " . $state),
            ])
            ->actions([
                // 1. Tombol Download File Asli
                Action::make('download')
                    ->label('Download PDF/DOC')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn (Submission $record) => Storage::disk('local')->download($record->manuscript_file)),
                
                Action::make('publish_paper')
                    ->label('Publish Paper')
                    ->icon('heroicon-o-megaphone')
                    ->color('primary')
                    // Syarat: Sudah Diterima, Sudah Lunas, dan Belum Terbit
                    ->visible(fn ($record) => 
                        $record->status->value === 'paid' && 
                        $record->payment_status === 'paid'
                    )
                    ->form([
                        Forms\Components\Select::make('journal_issue_id')
                            ->label('Select Volume / Issue')
                            ->options(fn () => \App\Models\JournalIssue::where('journal_theme_id', \Filament\Facades\Filament::getTenant()->id)
                                ->get()
                                ->mapWithKeys(fn ($issue) => [$issue->id => "{$issue->volume} No. {$issue->issue} ({$issue->year})"]))
                            ->required()
                            ->helperText('Pilih ke dalam volume mana naskah ini akan diterbitkan.'),
                        Forms\Components\TextInput::make('doi')
                            ->label('Digital Object Identifier (DOI)')
                            ->placeholder('e.g., 10.1234/agromix.v1i1.123')
                            ->helperText('Masukkan nomor DOI resmi untuk paper ini.'),
                    ])
                    ->action(function ($record, array $data) {
                        // 1. Update Status dan Hubungkan ke Issue
                        $record->update([
                            'status' => \App\Enums\SubmissionStatus::PUBLISHED,
                            'journal_issue_id' => $data['journal_issue_id'],
                            'doi' => $data['doi'],
                        ]);

                        // 2. Kirim Email Selamat ke Author
                        $mailable = new \App\Mail\PaperPublishedNotification($record, "Congratulations! Your research is now officially published and accessible through our digital library.");
                        \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                        \Filament\Notifications\Notification::make()
                            ->title('Paper Published Successfully!')
                            ->success()
                            ->send();
                    }),

                // 2. TOMBOL REQUEST REVISION (Ini yang kamu cari!)
                Action::make('request_revision')
                    ->label('Request Revision')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    // Syarat tombol muncul: hanya saat naskah sedang di-review
                    ->visible(fn (Submission $record) => $record->status === SubmissionStatus::UNDER_REVIEW)
                    ->mountUsing(fn (Forms\ComponentContainer $form, Submission $record) => $form->fill([
                        // Tarik otomatis semua komentar reviewer untuk disunting manager
                        'revision_notes' => $record->reviews->pluck('notes_for_author')->filter()->implode("\n\n---\n\n"),
                    ]))
                    ->form([
                        Textarea::make('revision_notes')
                            ->label('Revision Instructions for Author')
                            ->helperText('This official message will be sent and visible to the Author.')
                            ->rows(8)
                            ->required(),
                    ])
                    ->action(function (Submission $record, array $data): void {
                        $record->update([
                            'status' => SubmissionStatus::REVISION_REQUIRED,
                            'revision_notes' => $data['revision_notes'],
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Revision request sent to Author')
                            ->success()
                            ->send();
                    }),
                Action::make('download_payment')
                    ->label('Cek Struk Transfer')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('info')
                    ->visible(fn (Submission $record) => $record->payment_proof !== null)
                    ->action(fn (Submission $record) => Storage::disk('local')->download($record->payment_proof)),

                Action::make('confirm_payment')
                    ->label('Verify & Confirm Payment')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    // Tombol hanya muncul jika sudah Accepted DAN pembayaran sedang menunggu verifikasi
                    ->visible(fn ($record) => 
                        $record->status->value === 'accepted' && 
                        ($record->payment_status === 'unpaid' || $record->payment_status === 'pending_verification')
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Author Payment?')
                    ->modalDescription('By confirming, the status will change to PAID, and an official confirmation email will be sent to the author.')
                    ->action(function ($record) {
                        // 1. Update Status Pembayaran
                        $record->update([
                            'payment_status' => 'paid',
                        ]);

                        // 2. Draft Pesan Email
                        $pesan = "Thank you for completing the registration payment. Your Letter of Acceptance (LOA) is now available for download in your dashboard.";

                        // 3. Kirim Email via Job (Background)
                        $mailable = new \App\Mail\PaymentConfirmedNotification($record, $pesan);
                        \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                        \Filament\Notifications\Notification::make()
                            ->title('Payment Verified Successfully')
                            ->success()
                            ->send();
                    }),

                // 3. Tombol View & Edit Bawaan
                ViewAction::make(),
                // EditAction::make()->label('Process'),
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