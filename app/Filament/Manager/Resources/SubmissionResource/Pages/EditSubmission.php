<?php

namespace App\Filament\Manager\Resources\SubmissionResource\Pages;

use App\Filament\Manager\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use App\Enums\SubmissionStatus;

class EditSubmission extends EditRecord
{
    protected static string $resource = SubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ==========================================
            // GROUP 1: DOKUMEN (Naskah & Struk)
            // ==========================================
            Actions\ActionGroup::make([
                Actions\Action::make('view_manuscript')
                    ->label('Lihat Naskah (PDF)')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    // FIX: Gunakan $this->getRecord()
                    ->url(fn () => route('secure.file', encrypt(['id' => $this->getRecord()->id, 'type' => 'manuscript'])))
                    ->openUrlInNewTab(),

                Actions\Action::make('view_payment')
                    ->label('Cek Struk Pembayaran')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->visible(fn () => $this->getRecord()->payment_proof !== null)
                    ->url(fn () => route('secure.file', encrypt(['id' => $this->getRecord()->id, 'type' => 'payment'])))
                    ->openUrlInNewTab(),
            ])
            ->label('Lihat Dokumen')
            ->icon('heroicon-o-folder-open')
            ->button()
            ->color('gray'),

            // ==========================================
            // GROUP 2: KEPUTUSAN REVIEW
            // ==========================================
            Actions\ActionGroup::make([
                Actions\Action::make('accept_submission')
                    ->label('Terima Naskah (Accept)')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn () => !in_array($this->getRecord()->status->value, ['accepted', 'rejected', 'paid', 'published']))
                    ->mountUsing(function (Form $form) {
                        $draft = "The editorial board is impressed with the quality of your work. We believe it makes a significant contribution to the field.";
                        $form->fill(['custom_email' => $draft]);
                    })
                    ->form([
                        Textarea::make('custom_email')->label('Additional Notes (English)')->rows(6)->required()
                    ])
                    ->action(function (array $data) {
                        $record = $this->getRecord();
                        $record->update(['status' => SubmissionStatus::ACCEPTED]);

                        $mailable = new \App\Mail\PaperAcceptedNotification($record, $data['custom_email']);
                        \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                        \Filament\Notifications\Notification::make()->title('Submission Accepted & Email Queued')->success()->send();
                        redirect(request()->header('Referer')); 
                    }),

                Actions\Action::make('request_revision')
                    ->label('Minta Revisi (Revision)')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn () => $this->getRecord()->status->value === 'under_review')
                    ->mountUsing(function (Form $form) {
                        $reviewerNotes = $this->getRecord()->reviews->pluck('notes_for_author')->filter()->implode("\n\n---\n\n");
                        $form->fill(['custom_email' => $reviewerNotes]);
                    })
                    ->form([
                        Textarea::make('custom_email')->label('Reviewer Feedback (English)')->rows(8)->required()
                    ])
                    ->action(function (array $data) {
                        $record = $this->getRecord();
                        $record->update([
                            'status' => SubmissionStatus::REVISION_REQUIRED,
                            'revision_notes' => $data['custom_email'],
                        ]);

                        $mailable = new \App\Mail\PaperRevisionNotification($record, $data['custom_email']);
                        \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                        \Filament\Notifications\Notification::make()->title('Revision Requested & Email Queued')->warning()->send();
                        redirect(request()->header('Referer')); 
                    }),

                Actions\Action::make('reject_submission')
                    ->label('Tolak Naskah (Reject)')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn () => !in_array($this->getRecord()->status->value, ['accepted', 'rejected', 'paid', 'published']))
                    ->mountUsing(function (Form $form) {
                        $draft = "Unfortunately, your manuscript does not fit the scope of our current issue.\n";
                        $draft .= "Additionally, the methodology section lacks the necessary details required for replication.";
                        $form->fill(['custom_email' => $draft]);
                    })
                    ->form([
                        Textarea::make('custom_email')->label('Reason for Rejection')->rows(6)->required()
                    ])
                    ->action(function (array $data) {
                        $record = $this->getRecord();
                        $record->update([
                            'status' => SubmissionStatus::REJECTED,
                            'revision_notes' => $data['custom_email'],
                        ]);

                        $mailable = new \App\Mail\PaperRejectedNotification($record, $data['custom_email']);
                        \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                        \Filament\Notifications\Notification::make()->title('Naskah Ditolak & Email Dikirim!')->danger()->send();
                        redirect(request()->header('Referer')); 
                    }),
            ])
            ->label('Keputusan Review')
            ->icon('heroicon-o-scale')
            ->button()
            ->color('warning')
            // FIX: ActionGroup sekarang menggunakan $this->getRecord()
            ->visible(fn () => !in_array($this->getRecord()->status->value, ['accepted', 'rejected', 'paid', 'published'])),

            // ==========================================
            // STANDALONE: VERIFIKASI PEMBAYARAN
            // ==========================================
            Actions\Action::make('verify_payment_header')
                ->label('Verifikasi Pembayaran')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => 
                    $this->getRecord()->status->value === 'accepted' && 
                    ($this->getRecord()->payment_status === 'pending_verification' || $this->getRecord()->payment_status === 'unpaid')
                )
                ->requiresConfirmation()
                ->action(function () {
                    $record = $this->getRecord();
                    $record->update([
                        'payment_status' => 'paid',
                        'status' => SubmissionStatus::PAID // Naik level ke PAID
                    ]);

                    $mailable = new \App\Mail\PaymentConfirmedNotification($record, "Payment verified. Official documents are now available.");
                    \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                    \Filament\Notifications\Notification::make()->title('Pembayaran Terverifikasi')->success()->send();
                    return redirect(request()->header('Referer'));
                }),

            // ==========================================
            // GROUP 3: MANAJEMEN PUBLIKASI
            // ==========================================
            Actions\ActionGroup::make([
                Actions\Action::make('publish_paper')
                    ->label('Terbitkan Jurnal (Publish)')
                    ->icon('heroicon-o-megaphone')
                    ->color('primary')
                    ->visible(fn () => $this->getRecord()->status->value === 'paid')
                    ->form([
                        \Filament\Forms\Components\Select::make('journal_issue_id')
                            ->label('Pilih Volume / Issue')
                            ->options(fn () => \App\Models\JournalIssue::where('journal_theme_id', \Filament\Facades\Filament::getTenant()->id)
                                ->get()
                                ->mapWithKeys(fn ($issue) => [$issue->id => "{$issue->volume} No. {$issue->issue} ({$issue->year})"]))
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('doi')
                            ->label('Nomor DOI')
                            ->placeholder('e.g., 10.1234/agromix.v1i1.123'),
                    ])
                    ->action(function (array $data) {
                        $record = $this->getRecord();
                        $record->update([
                            'status' => SubmissionStatus::PUBLISHED,
                            'journal_issue_id' => $data['journal_issue_id'],
                            'doi' => $data['doi'],
                        ]);

                        $mailable = new \App\Mail\PaperPublishedNotification($record, "Congratulations! Your research is now officially published.");
                        \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                        \Filament\Notifications\Notification::make()->title('Jurnal Resmi Diterbitkan!')->success()->send();
                        return redirect(request()->header('Referer'));
                    }),

                Actions\Action::make('edit_publication')
                    ->label('Edit Volume & DOI')
                    ->icon('heroicon-o-pencil-square')
                    ->color('info')
                    ->visible(fn () => $this->getRecord()->status->value === 'published')
                    ->mountUsing(function (Form $form) {
                        $form->fill([
                            'journal_issue_id' => $this->getRecord()->journal_issue_id,
                            'doi' => $this->getRecord()->doi,
                        ]);
                    })
                    ->form([
                        \Filament\Forms\Components\Select::make('journal_issue_id')
                            ->label('Pilih Volume / Issue')
                            ->options(fn () => \App\Models\JournalIssue::where('journal_theme_id', \Filament\Facades\Filament::getTenant()->id)
                                ->get()
                                ->mapWithKeys(fn ($issue) => [$issue->id => "{$issue->volume} No. {$issue->issue} ({$issue->year})"]))
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('doi')
                            ->label('Nomor DOI'),
                    ])
                    ->action(function (array $data) {
                        $record = $this->getRecord();
                        $record->update([
                            'journal_issue_id' => $data['journal_issue_id'],
                            'doi' => $data['doi'],
                        ]);
                        \Filament\Notifications\Notification::make()->title('Data Publikasi Diperbarui')->success()->send();
                    }),

                Actions\Action::make('unpublish_paper')
                    ->label('Tarik Naskah (Unpublish)')
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->color('danger')
                    ->visible(fn () => $this->getRecord()->status->value === 'published')
                    ->requiresConfirmation()
                    ->modalHeading('Cabut Publikasi Naskah?')
                    ->modalDescription('Naskah akan ditarik dari halaman arsip publik dan statusnya dikembalikan menjadi Lunas (Paid).')
                    ->action(function () {
                        $record = $this->getRecord();
                        $record->update([
                            'status' => SubmissionStatus::PAID, 
                        ]);
                        \Filament\Notifications\Notification::make()->title('Publikasi Berhasil Dicabut')->danger()->send();
                        return redirect(request()->header('Referer'));
                    }),
            ])
            ->label('Atur Publikasi')
            ->icon('heroicon-o-globe-alt')
            ->button()
            ->color('primary')
            // FIX: ActionGroup sekarang menggunakan $this->getRecord()
            ->visible(fn () => in_array($this->getRecord()->status->value, ['paid', 'published'])),

            Actions\DeleteAction::make(),
        ];
    }
}