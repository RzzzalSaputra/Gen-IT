<?php

namespace App\Filament\Widgets;

use App\Models\Submission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class SubmissionWidget extends BaseWidget
{
    protected static bool $isLazy = true; // Biarkan widget ini lazy loading, cepat tapi ringan!

    public function getColumns(): int
    {
        return 2; // Biar layout lebih cakep dan nggak mepet!
    }

    protected function getStats(): array
    {
        // Ambil data statistik
        $pendingSubmissions = Submission::where('status', 41)->count();
        $approvedSubmissions = Submission::where('status', 42)->count();

        return [

            Stat::make('Pengajuan Belum Disetujui', $pendingSubmissions)
                ->description('Menunggu persetujuan')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Pengajuan Disetujui', $approvedSubmissions)
                ->description('Sudah disetujui')
                ->color('success')
                ->icon('heroicon-o-check-badge'),
        ];
    }
}