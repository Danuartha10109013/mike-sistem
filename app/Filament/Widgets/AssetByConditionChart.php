<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class AssetByConditionChart extends ChartWidget
{
    protected static ?string $heading = 'Asset By Condition';

    protected static ?int $sort = 2;

    public function getHeading(): string|Htmlable|null
    {
        return __('dashboard.widgets.charts.asset_by_condition.label');
    }

    protected function getData(): array
    {
        return [
            'labels' => ['New', 'Damaged', 'Used'],
            'datasets' => [
                [
                    'label' => 'My First Dataset',
                    'data' => [Asset::new()->count(), Asset::damaged()->count(), Asset::used()->count()],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
