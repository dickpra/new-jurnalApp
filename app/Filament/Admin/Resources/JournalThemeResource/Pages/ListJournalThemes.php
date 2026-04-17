<?php

namespace App\Filament\Admin\Resources\JournalThemeResource\Pages;

use App\Filament\Admin\Resources\JournalThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJournalThemes extends ListRecords
{
    protected static string $resource = JournalThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
