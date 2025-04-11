<?php

namespace App\Filament\Resources\ViconResource\Pages;

use App\Filament\Resources\ViconResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVicon extends EditRecord
{
    protected static string $resource = ViconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
