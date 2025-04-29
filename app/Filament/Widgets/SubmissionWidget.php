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
        $totalSubmissions = Submission::count();
        $pendingSubmissions = Submission::where('status', 41)->count();
        $approvedSubmissions = Submission::where('status', 42)->count();
        $todaySubmissions = Submission::whereDate('created_at', today())->count();

        return [
            Stat::make('Total Pengajuan', $totalSubmissions)
                ->description('Semua pengajuan yang tercatat')
                ->color('primary')
                ->icon('heroicon-o-document-text'),

            Stat::make('Pengajuan Pending', $pendingSubmissions)
                ->description('Menunggu persetujuan')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Pengajuan Disetujui', $approvedSubmissions)
                ->description('Sudah disetujui')
                ->color('success')
                ->icon('heroicon-o-check-badge'),

            Stat::make('Pengajuan Hari Ini', $todaySubmissions)
                ->description("Diajukan")
                ->color('warning')
                ->icon('heroicon-o-arrow-trending-up')
        ];
    }
}