<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\Option;
use App\Models\User;
use Filament\Forms;
use Illuminate\Support\Str;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $pluralLabel = 'Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Dropdown untuk memilih layout
                Forms\Components\Select::make('layout')
                    ->label('Layout')
                    ->options(Option::where('type', 'post_layout')->pluck('value', 'id'))
                    ->required(),

                // Kolom untuk judul
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Judul artikel maksimal 255 karakter dan wajib diisi.')
                    ->maxLength(255),

            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->unique(Post::class, 'slug', ignoreRecord: true) // Mengecek keunikan slug di tabel Post
                ->required()
                ->columnSpanFull()
                ->maxLength(255)
                ->helperText('Slug wajib unik dan akan digunakan di URL. Tekan Generate untuk membuat slug otomatis dari judul.')
                ->suffixAction(
                    Forms\Components\Actions\Action::make('generate_slug')
                        ->label('Generate')
                        ->color('primary')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (callable $set, callable $get) {
                            $title = $get('title');
                            if ($title) {
                                $set('slug', Str::slug($title)); // Membuat slug otomatis dari judul
                            }
                        })
                ),

            Forms\Components\RichEditor::make('content')
                ->label('Konten')
                ->required()
                ->columnSpanFull()
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->helperText('Isi utama dari artikel. Harus diisi ya!'),

                // Kolom untuk gambar (image) dengan upload
                Forms\Components\FileUpload::make('img')
                    ->label('Image')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->directory('posts')  // Menyimpan file dalam direktori posts
                    ->visibility('public')  // Menetapkan file sebagai publik
                    ->nullable()  // Memungkinkan untuk tidak mengunggah file
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
                    }),

                // Kolom untuk video (link eksternal)
                Forms\Components\TextInput::make('video_url')
                    ->label('External Video Link')
                    ->url()
                    ->nullable(),

                // Kolom untuk menyimpan ID pembuat post
                Forms\Components\Hidden::make('created_by')
                    ->default(Auth::id())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom untuk menampilkan judul
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),

                // Kolom untuk menampilkan gambar
                Tables\Columns\ImageColumn::make('img')
                    ->label('Image')
                    ->sortable(),

                // Kolom untuk menampilkan URL video
                Tables\Columns\TextColumn::make('video_url')
                    ->label('Video URL')
                    ->limit(50)
                    ->url(fn($state) => $state, true),

                // Kolom untuk menampilkan siapa yang membuat post
                Tables\Columns\TextColumn::make('created_by')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->formatStateUsing(fn($state) => User::find($state)?->name),

                // Kolom untuk tanggal pembuatan
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])  // Bisa menambahkan filter jika diperlukan
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');  // Menyortir berdasarkan tanggal pembuatan
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
