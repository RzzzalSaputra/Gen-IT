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
    protected static ?string $navigationLabel = 'Materials';
    protected static ?string $pluralLabel = 'Materials';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                
                Forms\Components\TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),
                
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->unique(Material::class, 'slug', ignoreRecord: true)
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

                Forms\Components\RichEditor::make('content')
                    ->label('Content')
                    ->required(),

            Forms\Components\FileUpload::make('file')
                ->label('File (PDF, DOC, etc.)')
                ->nullable()
                ->directory('materials/files')
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

                Forms\Components\FileUpload::make('img')
                    ->label('Thumbnail Image')
                    ->image()
                    ->directory('materials/images')
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

                Forms\Components\TextInput::make('link')
                    ->label('External Link')
                    ->url()
                    ->nullable(),

                Forms\Components\Select::make('layout')
                    ->label('Layout')
                    ->options(Option::where('type', 'layout')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options(Option::where('type', 'material_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('read_counter')
                    ->label('Read Counter')
                    ->numeric()
                    ->default(0)
                    ->disabled(),

                Forms\Components\TextInput::make('download_counter')
                    ->label('Download Counter')
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
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('layout')
                    ->label('Layout')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\ImageColumn::make('img')
                    ->label('Thumbnail')
                    ->circular(),

                Tables\Columns\TextColumn::make('read_counter')
                    ->label('Reads')
                    ->sortable(),

                Tables\Columns\TextColumn::make('download_counter')
                    ->label('Downloads')
                    ->sortable(),

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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}