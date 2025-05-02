<?php

namespace App\Filament\Resources\ViconResource\Pages;

use App\Filament\Resources\ViconResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateVicon extends CreateRecord
{
    protected static string $resource = ViconResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Video Conference Berhasil Dibuat')
            ->success()
            ->body("Yey! Video conference berhasil dibuat! (ﾉ◕ヮ◕)ﾉ*:･ﾟ✧");
    }
}
