<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListSubmissions extends ListRecords
{
    protected static string $resource = SubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Pengajuan Baru')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(SubmissionResource::getUrl('create')),
        ];
    }


    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),

            'pending' => Tab::make('Menunggu')
                ->modifyQueryUsing(fn($query) => $query->where('status', 41)),

            'approved' => Tab::make('Disetujui')
                ->modifyQueryUsing(fn($query) => $query->where('status', 42)),

            'rejected' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn($query) => $query->where('status', 43)),
        ];
    }
}
