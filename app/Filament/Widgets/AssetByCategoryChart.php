<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class AssetByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Asset By Category';

    protected static ?int $sort = 1;

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
