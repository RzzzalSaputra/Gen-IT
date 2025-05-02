<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Artikel';
    protected static ?string $pluralLabel = 'Artikel';
    protected static ?string $navigationGroup = 'Konten';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(columns: 2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Wajib diisi ya~ Judulnya buat artikelnya (⌒‿⌒). Maksimal 255 karakter.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->unique(Article::class, 'slug', ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Slug itu alamat artikelnya, jadi harus unik~ Tekan "Generate" biar otomatis muncul dari judulnya! (｡•̀ᴗ-)✧. Maksimal 255 karakter.')
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
                    ]),

                Forms\Components\RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->helperText('Isi artikelnya di sini yaa~ Jangan sampai kosong, pembaca udah nggak sabar (っ˘ω˘ς )'),

                Forms\Components\Textarea::make('summary')
                    ->label('Ringkasan')
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->nullable()
                    ->required()
                    ->helperText('Tulisin ringkasan manis tentang artikel ini~ max 500 karakter aja yaa (⁄ ⁄>⁄ ▽ ⁄<⁄ ⁄)'),

                Forms\Components\TextInput::make('writer')
                    ->label('Penulis')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Siapa penulisnya nih~? Tulisin namamu dengan bangga! (>ᴗ<)'),

                Forms\Components\DateTimePicker::make('post_time')
                    ->label('Tanggal Posting')
                    ->required()
                    ->helperText('Pilih kapan artikelnya tayang~ Biar bisa muncul pas waktunya tiba (๑˃ᴗ˂)ﻭ'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul'),

                Tables\Columns\TextColumn::make('post_time')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Posting'),

                Tables\Columns\TextColumn::make('creator.user_name')
                    ->label('Dibuat Oleh')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat Tanggal'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->title('Artikel Berhasil Dihapus')
                            ->success()
                            ->body('(≧◡≦) ♡ Bye-bye artikel, semoga ketemu lagi!')
                            ->danger()
                            ->icon('heroicon-o-trash')
                            ->iconPosition('left')
                            ->iconColor('danger')
                    ),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('post_time', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}