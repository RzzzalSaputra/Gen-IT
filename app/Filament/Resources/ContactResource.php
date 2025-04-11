<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;


class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Respond Contacts';
    protected static ?string $pluralLabel = 'Responses';

    public static function form(Form $form): Form
    {
        
        
        $authId = Auth::guard('web')->id();

        return $form
            ->schema([
                Forms\Components\Textarea::make('respond_message')
                    ->label('Response Message')
                    ->required()
                    ->maxLength(500),
                Forms\Components\Hidden::make('respond_by')
                    ->afterStateHydrated(fn($state, callable $set) => $set('respond_by', $authId))
                    ->required(),
                Forms\Components\Hidden::make('status')
                    ->afterStateHydrated(fn($state, callable $set) => $set('status', 2))
                    ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Sender')
                    ->sortable(),

                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('respond_message')
                    ->label('Response')
                    ->limit(50)
                    ->sortable(),

                Tables\Columns\TextColumn::make('responder.name')
                    ->label('Responded By')
                    ->sortable(),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->sortable()
                ->color(fn($record) => match ($record->status) {
                    1 => 'red',
                    2 => 'green',
                    default => 'gray',
                })
                ->formatStateUsing(fn($state) => match ($state) {
                    1 => 'Pending',
                    2 => 'Responded',
                    default => 'Unknown',
                }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(fn(Form $form) => self::form($form)),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}