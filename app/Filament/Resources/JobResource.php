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
    protected static ?string $navigationLabel = 'Jobs';
    protected static ?string $pluralLabel = 'Jobs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->options(Company::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('Job Title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Job Description')
                    ->required(),

                Forms\Components\Textarea::make('requirment')
                    ->label('Job Requirements')
                    ->required(),

                Forms\Components\TextInput::make('salary_range')
                    ->label('Salary Range')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('register_link')
                    ->label('Registration Link')
                    ->url()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Job Type')
                    ->options(Option::where('type', 'job_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('experience')
                    ->label('Experience Level')
                    ->options(Option::where('type', 'experience_level')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('work_type')
                    ->label('Work Type')
                    ->options(Option::where('type', 'work_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('read_counter')
                    ->label('Read Counter')
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
                    ->label('Company')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Job Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Job Type')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('experience')
                    ->label('Experience Level')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('work_type')
                    ->label('Work Type')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('salary_range')
                    ->label('Salary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('read_counter')
                    ->label('Views')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Posted At')
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}