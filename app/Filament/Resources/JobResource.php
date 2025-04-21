<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job;
use App\Models\Company;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Lowongan Kerja';
    protected static ?string $pluralLabel = 'Lowongan Kerja';
    protected static ?string $navigationGroup = 'Karir';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->label('Perusahaan')
                    ->options(Company::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('Judul Pekerjaan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Pekerjaan')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\Textarea::make('requirment')
                    ->label('Persyaratan Pekerjaan')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\TextInput::make('salary_range')
                    ->label('Rentang Gaji')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('register_link')
                    ->label('Link Pendaftaran')
                    ->url()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Jenis Pekerjaan')
                    ->options(Option::where('type', 'job_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('experience')
                    ->label('Tingkat Pengalaman')
                    ->options(Option::where('type', 'experience_level')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('work_type')
                    ->label('Tipe Kerja')
                    ->options(Option::where('type', 'work_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('read_counter')
                    ->label('Jumlah Dilihat')
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Pekerjaan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Pekerjaan')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('experience')
                    ->label('Tingkat Pengalaman')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('work_type')
                    ->label('Tipe Kerja')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('salary_range')
                    ->label('Gaji')
                    ->sortable(),

                Tables\Columns\TextColumn::make('read_counter')
                    ->label('Dilihat')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diposting Pada')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}