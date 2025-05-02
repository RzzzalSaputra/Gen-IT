<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudyResource\Pages;
use App\Models\Study;
use App\Models\School;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Program Studi';
    protected static ?string $pluralLabel = 'Program Studi';
    protected static ?string $navigationGroup = 'Pendidikan';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('school_id')
                    ->label('Sekolah')
                    ->options(School::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Nama Program Studi')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->required(),

                Forms\Components\TextInput::make('duration')
                    ->label('Durasi (contoh: 4 Tahun, 3 Bulan)')
                    ->required(),

                Forms\Components\TextInput::make('link')
                    ->label('Tautan Info Lebih Lanjut')
                    ->url()
                    ->nullable(),

                Forms\Components\FileUpload::make('img')
                    ->label('Gambar Program')
                    ->image()
                    ->directory('studies/images'),

                Forms\Components\Select::make('level')
                    ->label('Tingkat')
                    ->options(Option::where('type', 'study_level')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('read_counter')
                    ->label('Jumlah Dilihat')
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('school.name')
                    ->label('Sekolah')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Program')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('read_counter')
                    ->label('Jumlah Dilihat')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->title('Program Studi Berhasil Dihapus')
                            ->success()
                            ->body('(≧◡≦) ♡ Bye-bye program studi, semoga ketemu lagi!')
                            ->danger()
                            ->icon('heroicon-o-trash')
                            ->iconPosition('left')
                            ->iconColor('danger')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudies::route('/'),
            'create' => Pages\CreateStudy::route('/create'),
            'edit' => Pages\EditStudy::route('/{record}/edit'),
        ];
    }
}