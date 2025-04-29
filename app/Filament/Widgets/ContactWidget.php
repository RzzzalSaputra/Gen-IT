<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContactWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 2;

    public function getColumns(): int
    {
        return 2; // Biar layout lebih cakep dan nggak mepet!
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Kontak Belum Dibalas', Contact::where('status', 1)->count())
                ->description('Pesan yang menunggu respon')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('danger'),
                
            Stat::make('Kontak Sudah Dibalas', Contact::where('status', 2)->count())
                ->description('Pesan yang telah direspon')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
