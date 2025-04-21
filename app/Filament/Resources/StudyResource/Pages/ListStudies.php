<?php

namespace App\Filament\Resources\StudyResource\Pages;

use App\Filament\Resources\StudyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListStudies extends ListRecords
{
    protected static string $resource = StudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Studi Baru')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(StudyResource::getUrl('create')),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('SMA')
                ->modifyQueryUsing(
                    fn($query) => $query->where('level', 45) // Hanya level 44 untuk tab "Semua"
                ),

            'upcoming' => Tab::make('SMK')
                ->modifyQueryUsing(
                    fn($query) => $query->where('level', 46) // Hanya level 45 untuk tab "Terjadwal"
                ),

            'past' => Tab::make('D3')
                ->modifyQueryUsing(
                    fn($query) => $query->where('level', 47) // Hanya level 46 untuk tab "Selesai"
                ),

            'tab4' => Tab::make('D4')
                ->modifyQueryUsing(
                    fn($query) => $query->where('level', 48) // Hanya level 47 untuk tab 4
                ),

            'tab5' => Tab::make('S1')
                ->modifyQueryUsing(
                    fn($query) => $query->where('level', 49) // Hanya level 48 untuk tab 5
                ),

            'tab6' => Tab::make('S2')
                ->modifyQueryUsing(
                    fn($query) => $query->where('level', 50) // Hanya level 49 untuk tab 6
                ),

            'tab7' => Tab::make('S3')
                ->modifyQueryUsing(
                    fn($query) => $query->where('level', 51) // Hanya level 50 untuk tab 7
                ),
        ];
    }
}
