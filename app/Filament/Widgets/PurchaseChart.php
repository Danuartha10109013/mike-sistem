<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class PurchaseChart extends ChartWidget
{
    public function getHeading(): string|Htmlable|null
    {
        return __('dashboard.widgets.charts.purchase_cost.label');
    }


    protected function getData(): array
    {
        $months = collect([
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
        ]);

        $data = Purchase::query()
            ->selectRaw('SUM(total) as total, MONTH(created_at) as month')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        return [
            'datasets' => [
                [
                    'label' => 'Maintenance Cost',
                    'data' => $months->map(function ($month, $index) use ($data) {
                        return $data->get($index + 1, 0);
                    }),
                ],
            ],
            'labels' => $months->map(function ($month) use ($data) {
                return $month;
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
