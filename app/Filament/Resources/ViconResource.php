<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ViconResource\Pages;
use App\Models\User;
use App\Models\Vicon;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Str;
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
                    ->label('Judul')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('desc')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->required(),

                Forms\Components\FileUpload::make('img')
                    ->label('Gambar Thumbnail')
                    ->image()
                    ->directory('vicon/images')
                    ->visibility('public')
                    ->nullable()
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $timestamp = now()->format('Ymd_His');
                        $random = mt_rand(100, 999);

                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $slugName = Str::slug($originalName);

                        return "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";
                    })
                    ->deleteUploadedFileUsing(function ($record) {
                        $filePath = storage_path('app/public/' . $record?->img);

                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }),

                Forms\Components\DateTimePicker::make('time')
                    ->label('Waktu Terjadwal')
                    ->required(),

                Forms\Components\TextInput::make('link')
                    ->label('Tautan Meeting')
                    ->url()
                    ->required(),

                Forms\Components\FileUpload::make('file')
                    ->label('File (PDF, DOC, dll.)')
                    ->nullable()
                    ->directory('vicon/files')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $timestamp = now()->format('Ymd_His');
                        $random = mt_rand(100, 999);

                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $slugName = Str::slug($originalName);

                        return "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";
                    })
                    ->deleteUploadedFileUsing(function ($record) {
                        $filePath = storage_path('app/public/' . $record?->img);

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
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('desc')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('time')
                    ->label('Waktu Terjadwal')
                    ->sortable()
                    ->dateTime(),

                Tables\Columns\TextColumn::make('link')
                    ->label('Tautan Meeting')
                    ->url(fn(string $state): string => $state, true),

                Tables\Columns\TextColumn::make('created_by')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->formatStateUsing(fn($state) => User::find($state)?->name),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
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
