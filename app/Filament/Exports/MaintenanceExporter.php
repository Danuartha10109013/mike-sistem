<?php

namespace App\Filament\Exports;

use App\Models\Maintenance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MaintenanceExporter extends Exporter
{
    protected static ?string $model = Maintenance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('asset.name')->label('Asset'),
            ExportColumn::make('submission_date')->label('Tanggal Pengajuan'),
            ExportColumn::make('price')->label('Harga'),
            ExportColumn::make('quantity')->label('Jumlah'),
            ExportColumn::make('total')->label('Total'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your maintenance export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
