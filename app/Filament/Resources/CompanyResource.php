<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Companies';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')
                    ->required(),

            FileUpload::make('img')
                ->label('Company Image')
                ->image()
                ->directory('companies')
                ->visibility('public')
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    $timestamp = now()->format('Ymd_His');
                    $randomNumber = mt_rand(1, 1000);
                    return "{$randomNumber}_{$timestamp}.{$file->getClientOriginalExtension()}";
                })
                ->afterStateUpdated(
                    fn($state, callable $set) =>
                    is_string($state) && !empty($state) ? $set('img', "/storage/companies/{$state}") : null
                )
                ->deleteUploadedFileUsing(
                    fn($record) =>
                    $record && method_exists($record, 'deleteImage') ? ($record->deleteImage() ?? true) : null
                )
                ->nullable(),

            Forms\Components\TextInput::make('gmap')
                    ->label('Google Maps Link')
                    ->nullable(),
                Forms\Components\TextInput::make('province')
                    ->required(),
                Forms\Components\TextInput::make('city')
                    ->required(),
                Forms\Components\Textarea::make('address')
                    ->required(),
                Forms\Components\TextInput::make('website')
                    ->url()
                    ->nullable(),
                Forms\Components\TextInput::make('instagram')
                    ->nullable(),
                Forms\Components\TextInput::make('facebook')
                    ->nullable(),
                Forms\Components\TextInput::make('x')
                    ->label('Twitter/X')
                    ->nullable(),
                Forms\Components\TextInput::make('read_counter')
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province')
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->sortable(),
                Tables\Columns\TextColumn::make('website')
                    ->limit(30),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('province')
                    ->options(Company::query()->pluck('province', 'province')->unique()->toArray()),
            ])
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}