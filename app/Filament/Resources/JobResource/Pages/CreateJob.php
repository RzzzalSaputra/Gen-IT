<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Lowongan Kerja Berhasil Dibuat')
            ->success()
            ->body("Yey! Lowongan kerja berhasil dibuat! (ﾉ◕ヮ◕)ﾉ*:･ﾟ✧");
    }
}
