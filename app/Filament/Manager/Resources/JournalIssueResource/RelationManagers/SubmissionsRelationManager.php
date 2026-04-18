<?php

namespace App\Filament\Manager\Resources\JournalIssueResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use App\Models\Submission;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';
    protected static ?string $title = 'Daftar Artikel (Papers) di Edisi Ini';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Naskah & Penulis')
                    ->wrap()
                    ->weight('bold')
                    ->description(function (Submission $record) {
                        if (is_array($record->co_authors) && count($record->co_authors) > 0) {
                            $firstAuthor = $record->co_authors[0]['name'];
                            $etAl = count($record->co_authors) > 1 ? ' et al.' : '';
                            return "✍️ " . $firstAuthor . $etAl;
                        }
                        return "✍️ Belum ada data penulis";
                    }),
                
                Tables\Columns\TextColumn::make('doi')
                    ->label('DOI')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->copyable()
                    ->copyMessage('DOI disalin!')
                    ->default('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Kita hilangkan tombol Create karena memasukkan paper ke Volume dilakukan via tombol Publish di SubmissionResource
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download Naskah')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (Submission $record) {
                        if (!$record->manuscript_file || !Storage::disk('local')->exists($record->manuscript_file)) {
                            \Filament\Notifications\Notification::make()
                                ->title('File Tidak Ditemukan!')
                                ->danger()
                                ->send();
                            return;
                        }
                        return Storage::disk('local')->download($record->manuscript_file);
                    }),
                
                // Tombol pintasan untuk langsung melompat ke halaman Edit Naskah tersebut
                Tables\Actions\Action::make('edit_paper')
                    ->label('Detail Naskah')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->url(fn (Submission $record) => \App\Filament\Manager\Resources\SubmissionResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                //
            ]);
    }
}