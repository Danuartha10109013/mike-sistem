<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class DashboardChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'Dataset 1',
                    'data' => [65, 59, 80, 81, 56, 55, 40],
                ],
                [
                    'label' => 'Dataset 2',
                    'data' => [28, 48, 40, 19, 86, 27, 90],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
