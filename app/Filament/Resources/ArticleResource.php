<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Articles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(columns: 3)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(
                                fn($state, callable $set, callable $get) =>
                                // Pastikan slug hanya di-generate jika auto_slug aktif
                                $get('auto_slug') ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn(callable $get) => $get('auto_slug'))  // Disable slug jika auto_slug aktif
                            ->helperText('Klik tombol "Generate Slug" untuk membuat slug otomatis.'),

                        // Gunakan Actions sebagai wadah untuk tombol
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generate_slug')
                                ->label('Generate Slug')
                                ->action(function (callable $set, callable $get) {
                                    // Ambil nilai title dan generate slug
                                    $title = $get('title');
                                    if ($title) {
                                        $set('slug', Str::slug($title));
                                    }
                                })
                                ->color('primary'),
                        ])
                    ]),

                Forms\Components\RichEditor::make('content')
                    ->required(),

                Forms\Components\Textarea::make('summary')
                    ->maxLength(500)
                    ->nullable(),

                Forms\Components\TextInput::make('writer')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('post_time')
                    ->required(),

                Forms\Components\Select::make('created_by')
                    ->relationship('creator', 'user_name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('writer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('post_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.user_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([/* Filter jika diperlukan */])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
