<?php

namespace App\Filament\Admin\Pages;

use App\Models\SiteSetting;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'CMS & Site Settings';
    protected static ?string $title = 'Pengaturan Halaman Depan';
    protected static string $view = 'filament.admin.pages.manage-site-settings';
    
    // Urutan menu di sidebar panel Super Admin
    protected static ?int $navigationSort = 10; 

    public ?array $data = [];

    public function mount(): void
    {
        // Cari data setting ID 1, jika belum ada, buatkan otomatis
        $settings = SiteSetting::firstOrCreate(
            ['id' => 1], 
            [
                'site_name' => 'AGROMIX Journal',
                'hero_title' => 'Scientific Journal Platform of Yudharta University',
                'hero_subtitle' => 'Platform publikasi ilmiah terpadu untuk mendesiminasikan hasil penelitian.',
                'contact_email' => 'admin@yudharta.ac.id'
            ]
        );
        
        $this->form->fill($settings->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Header & Identitas Web')
                    ->description('Pengaturan tampilan teratas pada halaman publik.')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nama Website')
                            ->required(),
                        FileUpload::make('logo_path')
                            ->label('Logo Utama')
                            ->image()
                            ->directory('cms-assets'), // Tersimpan di storage/app/public/cms-assets
                        TextInput::make('hero_title')
                            ->label('Judul Utama (Hero)')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('hero_subtitle')
                            ->label('Sub-judul / Slogan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Informasi & Kontak')
                    ->schema([
                        Textarea::make('about_text')
                            ->label('Deskripsi Singkat Tentang Portal')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('contact_email')
                            ->label('Email Kontak')
                            ->email(),
                        TextInput::make('contact_phone')
                            ->label('Nomor Telepon'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $settings = SiteSetting::first();
        $settings->update($this->form->getState());

        Notification::make()
            ->success()
            ->title('Konfigurasi Halaman Depan Berhasil Disimpan')
            ->send();
    }
}