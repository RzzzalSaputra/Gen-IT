<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id(); // Pake Facade Auth
        return $data;
    }

        protected function getRedirectUrl(): string
        {
            return $this->getResource()::getUrl('index');
        }

        protected function getCreatedNotification(): ?Notification
        {
            return Notification::make()
                ->title('Artikel Berhasil Dibuat')
                ->success()
                ->body("Yey! Artikel berhasil dibuat! (ﾉ◕ヮ◕)ﾉ*:･ﾟ✧");
        }
}
