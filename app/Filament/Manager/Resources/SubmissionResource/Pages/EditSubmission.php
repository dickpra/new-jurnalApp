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
            // 1. Tombol VIEW Naskah di Tab Baru (Encrypted)
            Actions\Action::make('view_manuscript')
                ->label('View Manuscript')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn ($record) => route('secure.file', encrypt(['id' => $record->id, 'type' => 'manuscript'])))
                ->openUrlInNewTab(),

            // 2. Tombol VIEW Bukti Pembayaran (Encrypted)
            Actions\Action::make('view_payment')
                ->label('Cek Struk Pembayaran')
                ->icon('heroicon-o-currency-dollar')
                ->color('success')
                ->visible(fn ($record) => $record->payment_proof !== null)
                ->url(fn ($record) => route('secure.file', encrypt(['id' => $record->id, 'type' => 'payment'])))
                ->openUrlInNewTab(),

            Actions\Action::make('verify_payment_header')
            ->label('Verify Payment')
            ->icon('heroicon-o-check-badge')
            ->color('success')
            ->visible(fn ($record) => 
                $record->status->value === 'accepted' && 
                ($record->payment_status === 'pending_verification' || $record->payment_status === 'unpaid')
            )
            ->requiresConfirmation()
            ->action(function ($record) {
                $record->update(['payment_status' => 'paid']);
                $record->update(['status' => SubmissionStatus::PAID]);

                $mailable = new \App\Mail\PaymentConfirmedNotification($record, "Payment verified. Official documents are now available.");
                \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                \Filament\Notifications\Notification::make()
                    ->title('Pembayaran Terverifikasi')
                    ->success()
                    ->send();
                
                // Refresh halaman agar status berubah di layar
                return redirect(request()->header('Referer'));
            }),

            // ==========================================
            // TOMBOL KEPUTUSAN (DENGAN CUSTOM EMAIL & BACKGROUND JOB)
            // ==========================================

            // 1. TOMBOL TERIMA (ACCEPT)
            Actions\Action::make('accept_submission')
                ->label('Accept Submission')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn ($record) => !in_array($record->status->value, ['accepted', 'rejected', 'paid']))
                ->mountUsing(function (Form $form, $record) {
                    $draft = "The editorial board is impressed with the quality of your work. We believe it makes a significant contribution to the field.";
                    $form->fill(['custom_email' => $draft]);
                })
                ->form([
                    Textarea::make('custom_email')->label('Additional Notes (English)')->rows(6)->required()
                ])
                ->action(function ($record, array $data) {
                    $record->update(['status' => SubmissionStatus::ACCEPTED]);

                    // Kirim Mailable via Job
                    $mailable = new \App\Mail\PaperAcceptedNotification($record, $data['custom_email']);
                    \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                    \Filament\Notifications\Notification::make()->title('Submission Accepted & Email Queued')->success()->send();
                    redirect(request()->header('Referer')); 
                }),

            // 2. TOMBOL TOLAK (REJECT)
            Actions\Action::make('reject_submission')
                ->label('Reject Submission')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => !in_array($record->status->value, ['accepted', 'rejected', 'paid']))
                ->mountUsing(function (Form $form, $record) {
                    // SEKARANG DRAFT-NYA CUKUP ALASAN SPESIFIKNYA SAJA (Karena template awalnya sudah ada di Blade)
                    $draft = "Unfortunately, your manuscript does not fit the scope of our current issue.\n";
                    $draft .= "Additionally, the methodology section lacks the necessary details required for replication.";
                    
                    $form->fill(['custom_email' => $draft]);
                })
                ->form([
                    Textarea::make('custom_email')
                        ->label('Reason for Rejection (Will be inserted into the email template)')
                        ->rows(6)
                        ->required()
                ])
                ->action(function ($record, array $data) {
                    $record->update([
                        'status' => \App\Enums\SubmissionStatus::REJECTED,
                        'revision_notes' => $data['custom_email'],
                    ]);

                    // PENGIRIMAN EMAIL YANG SUPER CLEAN & PROFESIONAL
                    $mailable = new \App\Mail\PaperRejectedNotification($record, $data['custom_email']);
                    \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                    \Filament\Notifications\Notification::make()->title('Naskah Ditolak & Email Dikirim!')->danger()->send();
                    redirect(request()->header('Referer')); 
                }),

            // 3. TOMBOL REQUEST REVISION
            Actions\Action::make('request_revision')
                ->label('Request Revision')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn ($record) => $record->status->value === 'under_review')
                ->mountUsing(function (Form $form, $record) {
                    $reviewerNotes = $record->reviews->pluck('notes_for_author')->filter()->implode("\n\n---\n\n");
                    $form->fill(['custom_email' => $reviewerNotes]);
                })
                ->form([
                    Textarea::make('custom_email')->label('Reviewer Feedback (English)')->rows(8)->required()
                ])
                ->action(function ($record, array $data) {
                    $record->update([
                        'status' => SubmissionStatus::REVISION_REQUIRED,
                        'revision_notes' => $data['custom_email'],
                    ]);

                    // Kirim Mailable via Job
                    $mailable = new \App\Mail\PaperRevisionNotification($record, $data['custom_email']);
                    \App\Jobs\SendEmailJob::dispatch($record->author->email, $mailable);

                    \Filament\Notifications\Notification::make()->title('Revision Requested & Email Queued')->warning()->send();
                    redirect(request()->header('Referer')); 
                }),

            Actions\DeleteAction::make(),
        ];
    }
}