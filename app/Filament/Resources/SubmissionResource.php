<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Models\Submission;
use App\Models\User;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Submissions';
    protected static ?string $pluralLabel = 'Submissions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn(?Submission $record) => $record !== null),

                Forms\Components\RichEditor::make('content')
                    ->label('Content')
                    ->nullable()
                    ->disabled(fn(?Submission $record) => $record !== null),

                Forms\Components\FileUpload::make('file')
                    ->label('File')
                    ->nullable()
                    ->directory('submissions/files')
                    ->disabled(fn(?Submission $record) => $record !== null)
                    ->hint(fn(?Submission $record) => $record ? 'File tidak bisa diubah setelah dibuat' : null),

                Forms\Components\TextInput::make('link')
                    ->label('External Link')
                    ->url()
                    ->nullable()
                    ->disabled(fn(?Submission $record) => $record !== null),

                Forms\Components\FileUpload::make('img')
                    ->label('Image')
                    ->image()
                    ->nullable()
                    ->directory('submissions/images')
                    ->disabled(fn(?Submission $record) => $record !== null),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options(Option::where('type', 'submission_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required()
                    ->disabled(fn(?Submission $record) => $record !== null),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(Option::where('type', 'submission_status')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('approve_at')
                    ->label('Approval Date')
                    ->nullable(),

                Forms\Components\Hidden::make('approve_by')
                    ->default(fn() => Auth::id()),

                Forms\Components\Select::make('created_by')
                    ->label('Created By')
                    ->options(User::pluck('user_name', 'id'))
                    ->searchable()
                    ->default(Auth::id())
                    ->required()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('file')
                    ->label('File')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $cleanPath = ltrim(str_replace('storage/', '', $state), '/');
                        $url = asset('storage/' . $cleanPath);
                        return "<a href=\"{$url}\" target=\"_blank\" class=\"text-primary underline\">Watch</a>";
                    })
                    ->html()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value),

                Tables\Columns\TextColumn::make('approve_by')
                    ->label('Approved By')
                    ->sortable()
                    ->formatStateUsing(fn($state) => User::find($state)?->user_name ?? '-'),

                Tables\Columns\TextColumn::make('approve_at')
                    ->label('Approval Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_by')
                    ->label('Created By')
                    ->sortable()
                    ->formatStateUsing(fn($state) => User::find($state)?->user_name ?? '-'),

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
            'index' => Pages\ListSubmissions::route('/'),
            'create' => Pages\CreateSubmission::route('/create'),
            'edit' => Pages\EditSubmission::route('/{record}/edit'),
        ];
    }
}