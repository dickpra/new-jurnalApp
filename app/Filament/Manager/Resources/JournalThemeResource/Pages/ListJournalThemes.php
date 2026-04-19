<?php

namespace App\Filament\Manager\Resources\JournalThemeResource\Pages;

use App\Filament\Manager\Resources\JournalThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Facades\Filament;

class ListJournalThemes extends ListRecords
{
    protected static string $resource = JournalThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Kosongkan action create karena manager tidak boleh buat jurnal baru
        ];
    }

    // FUNGSI SAKTI UNTUK LANGSUNG LOMPAT KE HALAMAN EDIT
    public function mount(): void
    {
        parent::mount();

        // 1. Ambil data Tenant (Jurnal) yang sedang aktif dikelola oleh Manager ini
        $activeJournal = Filament::getTenant();

        // 2. Langsung tendang (redirect) otomatis ke halaman Edit milik jurnal tersebut!
        if ($activeJournal) {
            redirect(JournalThemeResource::getUrl('edit', ['record' => $activeJournal->id]));
        }
    }
}