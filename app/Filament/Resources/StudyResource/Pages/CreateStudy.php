<?php

namespace App\Filament\Resources\StudyResource\Pages;

use App\Filament\Resources\StudyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudy extends CreateRecord
{
    protected static string $resource = StudyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
