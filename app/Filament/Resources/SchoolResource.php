<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Models\School;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Schools';
    protected static ?string $pluralLabel = 'Schools';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('School Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required(),

                Forms\Components\FileUpload::make('img')
                    ->label('School Image')
                    ->image()
                    ->directory('schools')
                    ->visibility('public')
                    ->nullable()
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $timestamp = now()->format('Ymd_His');
                        $random = mt_rand(100, 999);
                        return "{$random}_{$timestamp}.{$file->getClientOriginalExtension()}";
                    })
                    ->deleteUploadedFileUsing(function ($record) {
                        $filePath = storage_path('app/public/' . $record?->img);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }),

                Forms\Components\Select::make('type')
                    ->label('School Type')
                    ->options(Option::where('type', 'school_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('gmap')
                    ->label('Google Maps Link')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('province')
                    ->label('Province')
                    ->required(),

                Forms\Components\TextInput::make('city')
                    ->label('City')
                    ->required(),

                Forms\Components\Textarea::make('address')
                    ->label('Address')
                    ->required(),

                Forms\Components\TextInput::make('website')
                    ->label('Website')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('instagram')
                    ->label('Instagram')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('facebook')
                    ->label('Facebook')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('x')
                    ->label('X (Twitter)')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('read_counter')
                    ->label('View Counter')
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('School Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('province')
                    ->label('Province')
                    ->sortable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('City')
                    ->sortable(),

                Tables\Columns\TextColumn::make('read_counter')
                    ->label('Views')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}