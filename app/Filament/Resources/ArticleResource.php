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

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Articles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(columns: 2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->unique(Article::class, 'slug', ignoreRecord: true)
                    ->required()
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
                    ->required(),

                Forms\Components\Textarea::make('summary')
                    ->maxLength(500)
                    ->nullable()
                    ->required(),

                Forms\Components\TextInput::make('writer')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('post_time')
                    ->required(),

                // Tidak perlu tampilkan created_by dan updated_by di form
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('writer')->searchable(),
                Tables\Columns\TextColumn::make('post_time')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('creator.user_name')->label('Dibuat Oleh')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
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