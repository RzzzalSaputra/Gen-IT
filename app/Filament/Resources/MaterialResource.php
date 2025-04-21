<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Models\Material;
use App\Models\Option;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Materi';
    protected static ?string $pluralLabel = 'Materi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->unique(Material::class, 'slug', ignoreRecord: true)
                    ->required()
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('generate_slug')
                            ->label('Buat Otomatis')
                            ->color('primary')
                            ->icon('heroicon-o-arrow-path')
                            ->action(function (callable $set, callable $get) {
                                $title = $get('title');
                                if ($title) {
                                    $set('slug', Str::slug($title));
                                }
                            })
                    ),

                Forms\Components\Select::make('layout')
                    ->label('Tata Letak')
                    ->options(Option::where('type', 'layout')->pluck('value', 'id'))
                    ->required()
                    ->reactive(),

                Forms\Components\RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull()
                    ->visible(fn(callable $get) => (int)$get('layout') === 9 || (int)$get('layout') === 10),

                Forms\Components\FileUpload::make('img')
                    ->label('Gambar Thumbnail')
                    ->image()
                    ->directory('materials/images')
                    ->visibility('public')
                    ->nullable()
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    $timestamp = now()->format('Ymd_His');
                    $random = mt_rand(100, 999);

                    // Mengambil nama asli file dan mengubahnya menjadi slug
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $slugName = Str::slug($originalName);

                    // Membuat nama file dengan format random_slug_timestamp.extension
                    return "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";
                })
                ->deleteUploadedFileUsing(function ($record) {
                    // Mengakses path file yang benar di database (sesuaikan dengan nama kolom yang digunakan)
                    $filePath = storage_path('app/public/' . $record?->file);  // Pastikan ini mengakses kolom yang benar

                    // Hapus file jika ada
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                })
                    ->visible(fn(callable $get) => (int)$get('layout') === 10),

                Forms\Components\FileUpload::make('file')
                    ->label('File (PDF, DOC, dll.)')
                    ->nullable()
                    ->directory('materials/files')
                    ->visibility('public')
                    ->openable()
                    ->downloadable()
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $random = mt_rand(100, 999);
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        return "{$random}_{$originalName}.{$extension}";
                    })
                    ->deleteUploadedFileUsing(function ($record) {
                        $filePath = storage_path('app/public/' . $record?->file);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('file_preview_url', asset('storage/' . $state));
                        }
                    })
                    ->visible(fn(callable $get) => (int)$get('layout') === 12),

                Forms\Components\TextInput::make('link')
                    ->label('Link Video (YouTube)')
                    ->url()
                    ->nullable()
                    ->visible(fn(callable $get) => (int)$get('layout') === 11),

                Forms\Components\Select::make('type')
                    ->label('Jenis Materi')
                    ->options(Option::where('type', 'material_type')->pluck('value', 'id'))
                    ->required(),

                Forms\Components\TextInput::make('read_counter')
                    ->label('Jumlah Dibaca')
                    ->numeric()
                    ->default(0)
                    ->disabled(),

                Forms\Components\TextInput::make('download_counter')
                    ->label('Jumlah Diunduh')
                    ->numeric()
                    ->default(0)
                    ->disabled(),

                Forms\Components\Hidden::make('created_by')
                    ->default(Auth::id())
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

                Tables\Columns\TextColumn::make('layout')
                    ->label('Tata Letak')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Materi')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\ImageColumn::make('img')
                    ->label('Thumbnail'),

                Tables\Columns\TextColumn::make('read_counter')
                    ->label('Dibaca')
                    ->sortable(),

                Tables\Columns\TextColumn::make('download_counter')
                    ->label('Diunduh')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_by')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->formatStateUsing(fn($state) => User::find($state)?->name),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}