<?php

namespace App\Filament\Widgets;

use App\Models\Commodity;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;

class DashboardStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            StatsOverviewWidget\Stat::make('Total Users', User::count())
                ->icon('heroicon-o-user-group')
                ->color('blue'),
            StatsOverviewWidget\Stat::make('Total Commodities', Commodity::count())
                ->icon('heroicon-s-archive-box')
                ->color('green'),
            StatsOverviewWidget\Stat::make('Total Commodities in Stock', Commodity::where('quantity', '>', 0)->count())
                ->icon('heroicon-s-archive-box')
                ->color('yellow'),
        ];
    }
}
