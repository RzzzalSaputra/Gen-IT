<?php

namespace App\Filament\Resources\ViconResource\Pages;

use App\Filament\Resources\ViconResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListVicons extends ListRecords
{
    protected static string $resource = ViconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),

            'upcoming' => Tab::make('Terjadwal')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->whereDate('time', '>=', now()->toDateString())
                ),

            'past' => Tab::make('Selesai')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->whereDate('time', '<', now()->toDateString())
                ),
        ];
    }
}
