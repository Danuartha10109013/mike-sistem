<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class AssetByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 1;

    public function getHeading(): string|Htmlable|null
    {
        return __('dashboard.widgets.charts.asset_by_category.label');
    }

    protected function getData(): array
    {
        return [
            'labels' => Category::pluck('name'),
            'datasets' => [
                [
                    'label' => 'My First Dataset',
                    'data' => Category::withCount('assets')->get()->pluck('assets_count'),
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
