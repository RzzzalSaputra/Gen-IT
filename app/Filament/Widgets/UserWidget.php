<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserWidget extends BaseWidget
{
    public function getColumns(): int
    {
        return 1;
    }
    
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Pengguna', User::count())
                ->description('Total pengguna terdaftar')
                ->icon('heroicon-o-users')
                ->color('success')
                ->chart([1, 3, 5, 10, 20, 30, 40])
                ,
        ];
    }
}
