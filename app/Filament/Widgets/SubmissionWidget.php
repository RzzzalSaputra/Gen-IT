<?php

namespace App\Filament\Widgets;

use App\Models\Submission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubmissionWidget extends BaseWidget
{
    public function getColumns(): int
    {
        return 1;
    }
    protected function getStats(): array
    {
        return [

            Stat::make('Jumlah Pengajuan', Submission::count())
                ->description('Total pengajuan terdaftar')
                ->icon('heroicon-o-document')
                ->color('success')
                ->chart([1, 3, 5, 10, 20, 30, 40])
                ,
            //
        ];
    }
}
