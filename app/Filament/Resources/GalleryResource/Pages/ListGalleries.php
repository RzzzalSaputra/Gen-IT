<?php

namespace App\Filament\Resources\GalleryResource\Pages;

use App\Filament\Resources\GalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListGalleries extends ListRecords
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),

            'image' => Tab::make('Gambar')
                ->modifyQueryUsing(fn($query) => $query->where('type', 7)),

            'video' => Tab::make('Video')
                ->modifyQueryUsing(fn($query) => $query->where('type', 8)),
        ];
    }
}
