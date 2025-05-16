<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Models\School;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Sekolah';
    protected static ?string $pluralLabel = 'Sekolah';
    protected static ?string $navigationGroup = 'Pendidikan';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Sekolah')
                    ->required()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('description')
                    ->label('Deskripsi Sekolah')
                    ->required()
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ]),

                Forms\Components\FileUpload::make('img')
                    ->label('Gambar Sekolah')
                    ->image()
                    ->directory('schools/images')
                    ->visibility('public')
                    ->nullable()
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $timestamp = now()->format('Ymd_His');
                        $random = mt_rand(100, 999);
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $slugName = \Illuminate\Support\Str::slug($originalName);
                        return "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";
                    })
                    ->deleteUploadedFileUsing(function ($record) {
                        $filePath = storage_path('app/public/' . $record?->img);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }),

                Forms\Components\Select::make('type')
                    ->label('Tipe Sekolah')
                    ->options(Option::where('type', 'school_type')->pluck('value', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('gmap')
                    ->label('Google Maps Link')
                    ->nullable(),

                Forms\Components\TextInput::make('province')
                    ->label('Provinsi')
                    ->required(),

                Forms\Components\TextInput::make('city')
                    ->label('Kota')
                    ->required(),

                Forms\Components\Textarea::make('address')
                    ->label('Alamat')
                    ->required(),

                Forms\Components\TextInput::make('website')
                    ->label('Website')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('instagram')
                    ->label('Instagram')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('facebook')
                    ->label('Facebook')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('x')
                    ->label('X (Twitter)')
                    ->url()
                    ->nullable(),

                Forms\Components\TextInput::make('read_counter')
                    ->label('Jumlah Pembaca')
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
                    ->label('Nama Sekolah')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('province')
                    ->label('Provinsi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->sortable(),

                Tables\Columns\TextColumn::make('read_counter')
                    ->label('Jumlah Pembaca')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->title('Sekolah Berhasil Dihapus')
                            ->success()
                            ->body('(≧◡≦) ♡ Bye-bye sekolah, semoga ketemu lagi!')
                            ->danger()
                            ->icon('heroicon-o-trash')
                            ->iconPosition('left')
                            ->iconColor('danger')
                    ),
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}