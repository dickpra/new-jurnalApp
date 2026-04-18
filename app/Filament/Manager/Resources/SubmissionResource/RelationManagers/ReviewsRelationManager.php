<?php

namespace App\Filament\Manager\Resources\SubmissionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\User;
use App\Enums\ReviewRecommendation;
use Illuminate\Validation\Rules\Unique;
use App\Enums\SubmissionStatus;



class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';
    protected static ?string $title = 'Review Assignments & Comments';
    

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reviewer_id')
                    ->label('Select Reviewer')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    // LAPIS PERTAHANAN 2: Cegah Error SQL, ubah jadi validasi form
                    ->unique(
                        table: 'reviews',
                        column: 'reviewer_id',
                        modifyRuleUsing: function (Unique $rule, callable $get, RelationManager $livewire) {
                            return $rule
                                ->where('submission_id', $livewire->getOwnerRecord()->id)
                                ->where('round', $get('round'));
                        }
                    )
                    ->validationMessages([
                        'unique' => 'This reviewer has been assigned to review this submission in this round!',
                    ]),
                
                Forms\Components\Select::make('round')
                    ->label('Review Round')
                    ->options([
                        1 => 'Round 1',
                        2 => 'Round 2',
                        3 => 'Round 3',
                    ])
                    ->required()
                    ->live() // Wajib ada agar rule unique di atas otomatis mengecek saat ronde diubah
                    // LAPIS PERTAHANAN 1: Default otomatis ngikutin ronde naskah
                    ->default(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->current_round),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('Reviewer Name'),
                
                Tables\Columns\TextColumn::make('round')
                    ->label('Round')
                    ->badge(),

                Tables\Columns\TextColumn::make('recommendation')
                    ->label('Recommendation')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->getLabel() ?? 'Pending'),

                Tables\Columns\IconColumn::make('is_completed')
                    ->label('Done')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Assign New Reviewer')
                    ->icon('heroicon-o-user-plus')
                    // FIX LOGIC 1: Sembunyikan tombol kalau naskah sudah Tamat (Accepted/Rejected)
                    ->visible(fn (RelationManager $livewire) => !in_array($livewire->getOwnerRecord()->status->value, ['accepted', 'rejected', 'paid', 'published']))
                    ->after(function ($record) {
                        $record->submission->update([
                            'status' => SubmissionStatus::UNDER_REVIEW
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Status Naskah di-update ke Under Review!')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View Feedback')
                    ->form([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Placeholder::make('rec')
                                ->label('Recommendation')
                                ->content(fn ($record) => $record->recommendation?->getLabel() ?? 'N/A'),
                            Forms\Components\Textarea::make('notes_for_author')
                                ->label('Comments for Author')
                                ->rows(4)
                                ->disabled(),
                            Forms\Components\Textarea::make('notes_for_manager')
                                ->label('Private Notes for Manager')
                                ->rows(4)
                                ->disabled(),
                        ])
                    ]),
                
                // FIX LOGIC 2: Sembunyikan tombol Edit & Delete kalau naskah sudah Tamat (Arsip Permanen)
                Tables\Actions\EditAction::make()
                    ->visible(fn (RelationManager $livewire) => !in_array($livewire->getOwnerRecord()->status->value, ['accepted', 'rejected'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (RelationManager $livewire) => !in_array($livewire->getOwnerRecord()->status->value, ['accepted', 'rejected'])),
            ]);
    }
}