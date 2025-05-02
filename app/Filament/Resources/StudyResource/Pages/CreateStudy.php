<?php

namespace App\Filament\Resources\StudyResource\Pages;

use App\Filament\Resources\StudyResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateStudy extends CreateRecord
{
    protected static string $resource = StudyResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Program Studi Berhasil Dibuat')
            ->success()
            ->body("Yey! Program studi berhasil dibuat! (ﾉ◕ヮ◕)ﾉ*:･ﾟ✧");
    }
}
