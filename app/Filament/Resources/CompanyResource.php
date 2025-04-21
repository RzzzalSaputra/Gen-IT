<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Illuminate\Support\Str;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;

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
                ->label('Nama Perusahaan')
                ->required()
                ->helperText('Nama harus unik dan wajib diisi.')
                ->columnSpanFull()
                ->unique(ignoreRecord: true),

            Forms\Components\RichEditor::make('description')
                ->label('Deskripsi')
                ->required()
                ->helperText('Deskripsi perusahaan tidak boleh kosong.')
                ->columnSpanFull()
                ->disableToolbarButtons(['attachFiles']),

            FileUpload::make('img')
                ->label('Logo Perusahaan')
                ->image()
                ->directory('company')
                ->visibility('public')
                ->nullable()
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    $timestamp = now()->format('Ymd_His');
                    $random = mt_rand(100, 999);

                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $slugName = Str::slug($originalName);

                    return "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";
                })
                ->deleteUploadedFileUsing(function ($record) {
                    $filePath = storage_path('app/public/' . $record?->img);

                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }),

            Forms\Components\TextInput::make('province')
                ->label('Provinsi')
                ->required()
                ->helperText('Provinsi tempat perusahaan berada.'),

            Forms\Components\TextInput::make('city')
                ->label('Kota')
                ->required()
                ->helperText('Kota tempat perusahaan berada.'),

            Forms\Components\Textarea::make('address')
                ->label('Alamat')
                ->required()
                ->helperText('Alamat lengkap perusahaan.'),

            Forms\Components\TextInput::make('gmap')
                ->label('Google Maps Link')
                ->nullable()
                ->helperText('Opsional. Link lokasi di Google Maps.'),

            Forms\Components\TextInput::make('website')
                ->label('Website')
                ->url()
                ->nullable()
                ->helperText('Opsional. Pastikan link diawali dengan https://'),

            Forms\Components\TextInput::make('instagram')
                ->label('Instagram')
                ->nullable()
                ->helperText('Opsional. Link halaman Instagram.'),

            Forms\Components\TextInput::make('facebook')
                ->label('Facebook')
                ->nullable()
                ->helperText('Opsional. Link halaman Facebook.'),

            Forms\Components\TextInput::make('x')
                ->label('Twitter/X')
                ->nullable()
                ->helperText('Opsional. Username atau link Twitter/X.'),

            Forms\Components\TextInput::make('read_counter')
                ->label('Jumlah Pembaca')
                ->numeric()
                ->default(0)
                ->disabled()
                ->helperText('Akan terisi otomatis, tidak bisa diubah manual.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province')
                    ->label('Provinsi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
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