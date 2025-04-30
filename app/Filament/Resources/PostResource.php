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
    protected static ?string $navigationLabel = 'Postingan';
    protected static ?string $pluralLabel = 'Postingan';
    protected static ?string $navigationGroup = 'Konten';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('layout')
                    ->label('Layout')
                    ->options(Option::where('type', 'post_layout')->pluck('value', 'id'))
                    ->required()
                    ->reactive(),

                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->unique(Post::class, 'slug', ignoreRecord: true)
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('generate_slug')
                            ->label('Generate')
                            ->color('primary')
                            ->icon('heroicon-o-arrow-path')
                            ->action(function (callable $set, callable $get) {
                                $title = $get('title');
                                if ($title) {
                                    $set('slug', Str::slug($title));
                                }
                            })
                    ),

                Forms\Components\RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ]),

                Forms\Components\FileUpload::make('img')
                    ->label('Image')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->directory('posts/images')
                    ->visibility('public')
                    ->nullable()
                    ->visible(fn (callable $get) => $get('layout') == 35)
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $timestamp = now()->format('Ymd_His');
                        $random = mt_rand(100, 999);
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $slugName = Str::slug($originalName);
                        return "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";
                    })
                    ->deleteUploadedFileUsing(function ($record) {
                        if ($record && $record->img) {
                            $filePath = storage_path('app/public/' . $record->img);
                            if (is_file($filePath)) {
                                unlink($filePath);
                            }
                        }
                    }),

                Forms\Components\FileUpload::make('file')
                    ->label('File')
                    ->acceptedFileTypes([
                        'application/pdf',                                                         // PDF
                        'application/msword',                                                     // DOC
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
                        'application/vnd.ms-excel',                                               // XLS
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',      // XLSX
                        'application/vnd.ms-powerpoint',                                          // PPT
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation' // PPTX
                    ])
                    ->helperText('Mendukung file: PDF, Word, Excel, dan PowerPoint')
                    ->directory('posts/files')
                    ->visibility('public')
                    ->nullable()
                    ->visible(fn (callable $get) => $get('layout') == 37)
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $timestamp = now()->format('Ymd_His');
                        $random = mt_rand(100, 999);
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $slugName = Str::slug($originalName);
                        return "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";
                    })
                    ->deleteUploadedFileUsing(function ($record) {
                        if ($record && $record->file) {
                            $filePath = storage_path('app/public/' . $record->file);
                            if (is_file($filePath)) {
                                unlink($filePath);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('video_url')
                    ->label('External Video Link')
                    ->url()
                    ->nullable()
                    ->visible(fn (callable $get) => $get('layout') == 36),

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
