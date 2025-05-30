<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $pluralLabel = 'Pengguna';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_name')
                    ->label('Nama Pengguna')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(table: 'users', column: 'email', ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->required(fn(string $context): bool => $context === 'create')
                    ->regex('/^[0-9]+$/')
                    ->rules(['min:10', 'max:13'])
                    ->prefixIcon('heroicon-m-phone')
                    ->helperText('Masukkan nomor tanpa +62 atau 0 di depan. Contoh: 81234567890')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '';
                        return ltrim(ltrim($state, '+62'), '0');
                    })
                    ->dehydrateStateUsing(function ($state) {
                        if (!$state) return null;
                        $number = preg_replace('/[^0-9]/', '', $state);
                        if (str_starts_with($number, '62')) {
                            $number = substr($number, 2);
                        }
                        return '+62' . ltrim($number, '0');
                    }),
                Forms\Components\TextInput::make('first_name')
                    ->label('Nama Depan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->label('Nama Belakang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birthdate')
                    ->label('Tanggal Lahir')
                    ->required(),
                Forms\Components\Select::make('role')
                    ->label('Peran')
                    ->required()
                    ->options([
                        'admin' => 'Admin',
                        'user' => 'User',
                        'teacher' => 'Guru',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_name')
                    ->label('Nama Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nama Depan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Nama Belakang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Peran')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->title('Pengguna Berhasil Dihapus')
                            ->success()
                            ->body('(≧◡≦) ♡ Bye-bye pengguna, semoga ketemu lagi!')
                            ->icon('heroicon-o-trash')
                            ->iconColor('danger')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
