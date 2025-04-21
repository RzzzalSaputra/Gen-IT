<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Materi Baru')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(MaterialResource::getUrl('create')),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),

            'text-only' => Tab::make('Text Only')
                ->modifyQueryUsing(fn($query) => $query->where('layout', 9)),

            'text-with-image' => Tab::make('Text + Image')
                ->modifyQueryUsing(fn($query) => $query->where('layout', 10)),

            'video-content' => Tab::make('Video')
                ->modifyQueryUsing(fn($query) => $query->where('layout', 11)),

            'file-only' => Tab::make('File Only')
                ->modifyQueryUsing(fn($query) => $query->where('layout', 12)),
        ];
    }
}
