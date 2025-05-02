<?php

namespace App\Filament\Resources\ViconResource\Pages;

use App\Filament\Resources\ViconResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditVicon extends EditRecord
{
    protected static string $resource = ViconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Video Conference Berhasil Diperbarui')
            ->success()
            ->body('✨ Oh, ini lebih keren sekarang! (︶^︶)');
    }
}
