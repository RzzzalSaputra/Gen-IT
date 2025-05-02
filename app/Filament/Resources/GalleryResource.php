<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use App\Models\Option;
use App\Models\User;
use Filament\Forms;
use Illuminate\Support\Str;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Galeri';
    protected static ?string $pluralLabel = 'Galeri';
    protected static ?string $navigationGroup = 'Konten';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('type')
                ->label('Jenis Galeri')
                ->options(Option::where('type', 'gallery_type')->pluck('value', 'id'))
                ->required()
                ->reactive()
                ->afterStateHydrated(function (Forms\Components\Select $component, $state, callable $get, callable $set) {
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
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

            Forms\Components\FileUpload::make('file')
                ->label('File (Image)')
                ->acceptedFileTypes([
                    'image/jpeg',   // JPG standar
                    'image/jpg',    // Alias dari JPEG
                    'image/png',    // Gambar transparan
                    'image/webp',   // Format modern, ukuran kecil
                    'image/gif',    // Bisa animasi
                    'image/svg+xml', // Gambar vektor
                    'image/bmp',    // Format lama, kualitas tinggi
                    'image/tiff',   // Gambar cetak profesional
                    'image/avif',   // Format baru, hemat ukuran
                    'image/heif',   // Umum di iPhone
                ])
                ->directory('galleries')
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
                ->visible(fn(callable $get) => Option::find($get('type'))?->value === 'image'),  // Hanya tampilkan jika tipe adalah 'image'


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
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('file')
                    ->label('Gambar')
                    ->size(350),

                Tables\Columns\TextColumn::make('link')
                    ->label('External Link')
                    ->limit(50)
                    ->url(fn($state) => $state, true),

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
                Tables\Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Galeri Berhasil Dihapus')
                        ->success()
                        ->body('(≧◡≦) ♡ Bye-bye galeri, semoga ketemu lagi!')
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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}