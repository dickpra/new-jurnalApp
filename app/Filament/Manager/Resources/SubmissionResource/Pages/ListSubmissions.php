<?php

namespace App\Filament\Manager\Resources\SubmissionResource\Pages;

use App\Filament\Manager\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\SubmissionStatus;
use Filament\Facades\Filament;

class ListSubmissions extends ListRecords
{
    protected static string $resource = SubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // FUNGSI SAKTI UNTUK MEMBUAT TAB FILTER
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Naskah')
                ->icon('heroicon-m-document-duplicate'),
                
            'pending' => Tab::make('Baru Masuk')
                ->icon('heroicon-m-inbox-arrow-down')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SubmissionStatus::PENDING))
                // Menambahkan angka (badge) di tab khusus naskah baru
                ->badge($this->getTabBadgeCount(SubmissionStatus::PENDING))
                ->badgeColor('danger'),

            'under_review' => Tab::make('Sedang Review')
                ->icon('heroicon-m-magnifying-glass')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SubmissionStatus::UNDER_REVIEW)),

            'revision' => Tab::make('Revisi')
                ->icon('heroicon-m-arrow-path')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SubmissionStatus::REVISION_REQUIRED)),

            'accepted' => Tab::make('Diterima (Unpaid)')
                ->icon('heroicon-m-check-badge')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SubmissionStatus::ACCEPTED)),

            'paid' => Tab::make('Lunas')
                ->icon('heroicon-m-currency-dollar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SubmissionStatus::PAID)),

            'published' => Tab::make('Terbit')
                ->icon('heroicon-m-globe-alt')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SubmissionStatus::PUBLISHED)),

            'rejected' => Tab::make('Ditolak')
                ->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', SubmissionStatus::REJECTED)),
        ];
    }

    // Fungsi bantuan agar badge menghitung naskah sesuai Jurnal yang sedang aktif
    protected function getTabBadgeCount($status)
    {
        $tenantId = Filament::getTenant()?->id;
        
        if (!$tenantId) return 0;

        return \App\Models\Submission::where('journal_theme_id', $tenantId)
            ->where('status', $status)
            ->count();
    }
}