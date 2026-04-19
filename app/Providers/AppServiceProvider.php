<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail; // TAMBAHKAN INI
use Illuminate\Notifications\Messages\MailMessage; // TAMBAHKAN INI


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // BAJAK EMAIL VERIFIKASI BAWAAN LARAVEL
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('SustainScript Email Verification')
                ->view('emails.verify-email', [
                    'url' => $url, 
                    'user' => $notifiable
                ]);
        });
    }
}
