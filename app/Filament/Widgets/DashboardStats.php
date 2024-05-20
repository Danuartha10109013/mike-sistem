<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\Purchase;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;

class DashboardStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $assetValue = Asset::get()->sum(fn($asset) => $asset->price * $asset->quantity);


        // Maintenance
        $maintenanceValueLastMonth = Maintenance::approvedLastMonth()->get()->sum(fn($maintenance) => $maintenance->total);
        $maintenanceValueThisMonth = Maintenance::approvedThisMonth()->get()->sum(fn($maintenance) => $maintenance->total);
        $percentage = $this->calculateDifferentPercentage($maintenanceValueLastMonth, $maintenanceValueThisMonth);
        $plusPercentage = abs($percentage);
        $descriptionText = $percentage == 0 ? 'No Change' : ($percentage > 0 ? $plusPercentage . '% Increase' : $plusPercentage . '% Decrease');
        $descriptionIcon = $percentage == 0 ? 'heroicon-o-check-circle' : ($percentage > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down');
        $descriptionColor = $percentage == 0 ? 'secondary' : ($percentage > 0 ? 'danger' : 'success');

        // Purchase
        $purchaseValueLastMonth = Purchase::approvedLastMonth()->get()->sum(fn($purchase) => $purchase->total);
        $purchaseValueThisMonth = Purchase::approvedThisMonth()->get()->sum(fn($purchase) => $purchase->total);
        $percentage = $this->calculateDifferentPercentage($purchaseValueLastMonth, $purchaseValueThisMonth);
        $plusPercentage = abs($percentage);
        $purchaseDescriptionText = $percentage == 0 ? 'No Change' : ($percentage > 0 ? $plusPercentage . '% Increase' : $plusPercentage . '% Decrease');
        $purchaseDescriptionIcon = $percentage == 0 ? 'heroicon-o-check-circle' : ($percentage > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down');
        $purchaseDescriptionColor = $percentage == 0 ? 'secondary' : ($percentage > 0 ? 'danger' : 'success');

        return [
            StatsOverviewWidget\Stat::make(__('dashboard.widgets.stats.user.label'), User::count())
                ->icon('heroicon-o-user-group')
                ->color('blue'),
            StatsOverviewWidget\Stat::make(__('dashboard.widgets.stats.asset.label'), Asset::count())
                ->icon('heroicon-s-archive-box')
                ->color('green'),
            StatsOverviewWidget\Stat::make(__('dashboard.widgets.stats.asset_value.label'), 'Rp. ' . number_format($assetValue, 0, ',', '.'))
                ->icon('heroicon-s-currency-dollar')
                ->color('yellow'),
            StatsOverviewWidget\Stat::make(__('dashboard.widgets.stats.purchase_this_month.label'), 'Rp. ' . number_format($maintenanceValueThisMonth, 0, ',', '.'))
                ->icon('heroicon-o-calendar')
                ->description($descriptionText)
                ->descriptionIcon($descriptionIcon)
                ->color($descriptionColor),
            StatsOverviewWidget\Stat::make(__('dashboard.widgets.stats.maintenance_this_month.label'), 'Rp. ' . number_format($purchaseValueThisMonth, 0, ',', '.'))
                ->icon('heroicon-o-shopping-cart')
                ->description($purchaseDescriptionText)
                ->descriptionIcon($purchaseDescriptionIcon)
                ->color($purchaseDescriptionColor),

        ];
    }

    private function calculateDifferentPercentage($lastMonth, $thisMonth): string
    {
        if ($lastMonth == $thisMonth) {
            return 0;
        }

        if ($lastMonth == 0 && $thisMonth == 0) {
            return 0;
        }

        if ($lastMonth == 0 && $thisMonth > 0) {
            return 100;
        }

        if ($lastMonth > 0 && $thisMonth == 0) {
            return -100;
        }

        return (($thisMonth - $lastMonth) / $lastMonth) * 100;
    }
}
