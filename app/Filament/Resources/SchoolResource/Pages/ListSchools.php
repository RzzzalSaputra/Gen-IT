<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListSchools extends ListRecords
{
    protected static string $resource = SchoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Sekolah Baru')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(SchoolResource::getUrl('create')),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->modifyQueryUsing(
                    fn($query) => $query
                ),

            'school_type_18' => Tab::make('SMA')
                ->modifyQueryUsing(
                    fn($query) => $query->where('type', 18)
                ),

            'school_type_19' => Tab::make('SMK')
                ->modifyQueryUsing(
                    fn($query) => $query->where('type', 19)
                ),

            'school_type_20' => Tab::make('Universitas')
                ->modifyQueryUsing(
                    fn($query) => $query->where('type', 20)
                ),
        ];
    }
}
