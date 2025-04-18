<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ViconResource\Pages;
use App\Models\User;
use App\Models\Vicon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ViconResource extends Resource
{
    protected static ?string $model = Vicon::class;
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'Vicons';
    protected static ?string $pluralLabel = 'Vicons';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('desc')
                    ->label('Description')
                    ->required(),

            Forms\Components\FileUpload::make('img')
                ->label('Thumbnail Image')
                ->image()
                ->directory('vicon/images')
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

                Forms\Components\DateTimePicker::make('time')
                    ->label('Scheduled Time')
                    ->required(),

                Forms\Components\TextInput::make('link')
                    ->label('Meeting Link')
                    ->url()
                    ->required(),

            Forms\Components\FileUpload::make('file')
                ->label('File (PDF, DOC, etc.)')
                ->nullable()
                ->directory('vicon/files')
                ->visibility('public')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    $timestamp = now()->format('Ymd_His');
                    $random = mt_rand(100, 999);
                    return "{$random}_{$timestamp}.{$file->getClientOriginalExtension()}";
                })
                ->deleteUploadedFileUsing(function ($record) {
                    $filePath = storage_path('app/public/' . $record?->file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }),


            Forms\Components\Hidden::make('created_by')
                    ->default(fn() => Auth::id())
                    ->required(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('desc')
                    ->label('Description')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('img')
                    ->label('Image')
                    ->circular(),

                Tables\Columns\TextColumn::make('time')
                    ->label('Scheduled Time')
                    ->sortable()
                    ->dateTime(),

                Tables\Columns\TextColumn::make('link')
                    ->label('Meeting Link')
                    ->url(fn(string $state): string => $state, true),

                Tables\Columns\TextColumn::make('created_by')
                    ->label('Created By')
                    ->sortable()
                    ->formatStateUsing(fn($state) => User::find($state)?->name),

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
            'index' => Pages\ListVicons::route('/'),
            'create' => Pages\CreateVicon::route('/create'),
            'edit' => Pages\EditVicon::route('/{record}/edit'),
        ];
    }
}
