<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CmsPageResource\Pages;
use App\Filament\Admin\Resources\CmsPageResource\RelationManagers;
use App\Models\CmsPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class CmsPageResource extends Resource
{
    protected static ?string $model = CmsPage::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate'; // Ikonnya lebih cocok
    protected static ?string $navigationLabel = 'Footer Pages';
    protected static ?string $modelLabel = 'Halaman CMS';
    protected static ?string $pluralModelLabel = 'Manajemen Konten Footer';
    protected static ?string $navigationGroup = 'Portal Settings';
    protected static ?int $navigationSort = 1;
    
    

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Page Structure')
                ->schema([
                    // INI DIA SIHIRNYA: Dropdown yang bisa bikin data baru!
                    Forms\Components\Select::make('footer_category_id')
                        ->label('Footer Menu Category')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Kategori Baru (Misal: Policies)')
                                ->required(),
                            Forms\Components\TextInput::make('sort_order')
                                ->label('Urutan Kategori (Angka)')
                                ->numeric()
                                ->default(0),
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                    
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                ])->columns(2),
                
            Forms\Components\Section::make('Page Content')
                ->schema([
                    Forms\Components\RichEditor::make('content')
                        ->required()
                        ->columnSpanFull()
                        ->fileAttachmentsDirectory('cms-content'),
                    
                    Forms\Components\Toggle::make('is_active')->default(true),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan Halaman di Bawah Kategori')
                        ->numeric()->default(0),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Judul Halaman
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Halaman')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Kolom Kategori (Menampilkan nama Parent/Kategori)
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori Footer')
                    ->badge() // Dibikin model badge/pil biar cantik
                    ->color('info')
                    ->sortable()
                    ->searchable(),

                // Kolom Toggle (Bisa langsung klik aktif/nonaktif dari tabel)
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status Tayang')
                    ->sortable(),

                // Kolom Urutan
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                // Kolom Tanggal (Sembunyi secara default, bisa dimunculin)
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter dropdown berdasarkan kategori (Policies, About, dll)
                Tables\Filters\SelectFilter::make('footer_category_id')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),
                
                // Filter Aktif / Nonaktif
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc'); // Urutkan otomatis berdasarkan angka urutan
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCmsPages::route('/'),
            'create' => Pages\CreateCmsPage::route('/create'),
            'edit' => Pages\EditCmsPage::route('/{record}/edit'),
        ];
    }
}
