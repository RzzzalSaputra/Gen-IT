<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

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

            'full_time' => Tab::make('Penuh Waktu')
                ->modifyQueryUsing(fn($query) => $query->where('type', 21)),

            'part_time' => Tab::make('Paruh Waktu')
                ->modifyQueryUsing(fn($query) => $query->where('type', 22)),

            'internship' => Tab::make('Magang')
                ->modifyQueryUsing(fn($query) => $query->where('type', 23)),

            'contract' => Tab::make('Kontrak')
                ->modifyQueryUsing(fn($query) => $query->where('type', 24)),

            'freelance' => Tab::make('Pekerja Lepas')
                ->modifyQueryUsing(fn($query) => $query->where('type', 25)),
        ];
    }
}
