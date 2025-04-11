<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use App\Models\Option;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Gallery';
    protected static ?string $pluralLabel = 'Galleries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('type')
                ->label('Gallery Type')
                ->options(Option::where('type', 'gallery_type')->pluck('value', 'id'))
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateHydrated(function (Forms\Components\Select $component, $state, callable $get, callable $set) {
                    // Kalau state type belum ada, kita coba atur otomatis berdasar field yg sudah terisi
                    if (!$state) {
                        if ($get('link')) {
                            $videoOption = Option::where('type', 'gallery_type')->where('value', 'video')->first();
                            if ($videoOption) {
                                $set('type', $videoOption->id);
                            }
                        } elseif ($get('file')) {
                            $imageOption = Option::where('type', 'gallery_type')->where('value', 'image')->first();
                            if ($imageOption) {
                                $set('type', $imageOption->id);
                            }
                        }
                    }
                }),

            Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                // Kalau tipe-nya image, tampilkan upload
                Forms\Components\FileUpload::make('file')
                    ->label('File (Image)')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->directory('gallery')
                    ->visibility('public')
                    ->nullable()
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
                    })
                    ->visible(fn(callable $get) => Option::find($get('type'))?->value === 'image'),

                // Kalau tipe-nya video, tampilkan input link
                Forms\Components\TextInput::make('link')
                    ->label('External Link (for Video)')
                    ->url()
                    ->nullable()
                    ->visible(fn(callable $get) => Option::find($get('type'))?->value === 'video'),

                Forms\Components\Hidden::make('created_by')
                    ->default(Auth::id())
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('file')
                    ->label('File')
                    ->circular(),

                Tables\Columns\TextColumn::make('link')
                    ->label('External Link')
                    ->limit(50)
                    ->url(fn($state) => $state, true),

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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}