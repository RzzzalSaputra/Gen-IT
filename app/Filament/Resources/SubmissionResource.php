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
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pengajuan';
    protected static ?string $pluralLabel = 'Pengajuan';

    public static function canCreate(): bool
    {
        return false;
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationGroup = 'Konten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('created_by')
                ->label('Disubmit Oleh')
                ->options(User::pluck('user_name', 'id'))
                ->disabled(),

                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn(?Submission $record) => $record !== null),

                Forms\Components\Select::make('type')
                    ->label('Tipe')
                    ->options(Option::where('type', 'submission_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required()
                    ->disabled(fn(?Submission $record) => $record !== null),

                Forms\Components\RichEditor::make('content')
                    ->label('Konten')
                    ->nullable()
                    ->disabled(fn(?Submission $record) => $record !== null),

                Forms\Components\FileUpload::make('file')
                    ->label('File')
                    ->nullable()
                    ->directory('submissions/files')
                    ->disabled(fn(?Submission $record) => $record !== null)
                    ->downloadable()
                    ->deletable(false)
                    ->openable()
                    ->visible(fn($get) => !empty($get('file'))),

                Forms\Components\TextInput::make('link')
                    ->label('External Link')
                    ->url()
                    ->nullable()
                    ->disabled(fn(?Submission $record) => $record !== null)
                    ->visible(fn($get) => !empty($get('link'))),

                Forms\Components\FileUpload::make('img')
                    ->label('Gambar')
                    ->image()
                    ->nullable()
                    ->directory('submissions/images')
                    ->disabled(fn(?Submission $record) => $record !== null)
                    ->visible(fn($get) => !empty($get('img'))),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(Option::where('type', 'submission_status')->pluck('value', 'id'))
                    ->required(),

                Forms\Components\DatePicker::make('approve_at')
                    ->label('Dijawab Tanggal')
                    ->nullable(),

                Forms\Components\Hidden::make('approve_by')
                    ->label('Dijawab Oleh')
                    ->default(fn() => Auth::id()),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Option::find($state)?->value ?? '-'),

                Tables\Columns\TextColumn::make('approve_by')
                    ->label('Dijawab Oleh')
                    ->sortable()
                    ->formatStateUsing(fn($state) => User::find($state)?->user_name ?? '-'),

                Tables\Columns\TextColumn::make('approve_at')
                    ->label('Dijawab Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_by')
                    ->label('Disubmit Oleh')
                    ->sortable()
                    ->formatStateUsing(fn($state) => User::find($state)?->user_name ?? '-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Disubmit Tanggal')
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
