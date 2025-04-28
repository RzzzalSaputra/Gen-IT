<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Pengguna Baru')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(UserResource::getUrl('create')),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->modifyQueryUsing(
                    fn($query) => $query
                ),

            'admin' => Tab::make('Admin')
                ->modifyQueryUsing(
                    fn($query) => $query->where('role', 'admin')
                ),

            'user' => Tab::make('User')
                ->modifyQueryUsing(
                    fn($query) => $query->where('role', 'user')
                ),

            'teacher' => Tab::make('Guru')  // Mengganti Manager menjadi Teacher
                ->modifyQueryUsing(
                    fn($query) => $query->where('role', 'teacher')
                ),
        ];
    }
}
