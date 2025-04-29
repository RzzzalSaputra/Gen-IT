<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),

            'pending' => Tab::make('Menunggu')
                ->modifyQueryUsing(fn($query) => $query->where('status', 1)),

            'responded' => Tab::make('Dibalas')
                ->modifyQueryUsing(fn($query) => $query->where('status', 2)),
        ];
    }
}
