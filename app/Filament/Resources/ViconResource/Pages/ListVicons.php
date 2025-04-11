<?php

namespace App\Filament\Resources\ViconResource\Pages;

use App\Filament\Resources\ViconResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVicons extends ListRecords
{
    protected static string $resource = ViconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
