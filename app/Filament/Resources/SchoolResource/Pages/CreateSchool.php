<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateSchool extends CreateRecord
{
    protected static string $resource = SchoolResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Sekolah Berhasil Dibuat')
            ->success()
            ->body("Yey! Sekolah berhasil dibuat! (ﾉ◕ヮ◕)ﾉ*:･ﾟ✧");
    }
}
