<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class UserWidget extends BaseWidget
{
    protected static bool $isLazy = true;

    public function getColumns(): int
    {
        return 3;
    }
    
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $teachers = User::where('role', 'teacher')->count();
        $regularUsers = User::where('role', 'user')->count();
        $newUsersThisMonth = User::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        
        return [
            Stat::make('Total Pengguna', $totalUsers)
                ->description('Semua pengguna terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->icon('heroicon-o-users')
                ->color('success')
                ->chart([
                    $totalUsers - 50, 
                    $totalUsers - 30, 
                    $totalUsers - 20, 
                    $totalUsers - 10, 
                    $totalUsers - 5, 
                    $totalUsers
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer transition hover:scale-105',
                ]),
                
            Stat::make('Pengguna Bulan Ini', $newUsersThisMonth)
                ->description('Bergabung di ' . Carbon::now()->format('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->icon('heroicon-o-user-plus')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer transition hover:scale-105',
                ]),
                
            Stat::make('Distribusi Peran', $admins + $teachers + $regularUsers)
                ->description("Admin: $admins | Guru: $teachers | User: $regularUsers")
                ->descriptionIcon('heroicon-m-chart-pie')
                ->icon('heroicon-o-user-circle')
                ->color('primary')
                ->chart([
                    $admins,
                    $teachers, 
                    $regularUsers
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer transition hover:scale-105',
                ]),
        ];
    }
}
