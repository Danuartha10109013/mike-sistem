<?php

namespace App\Filament\Exports;

use App\Models\Maintenance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

// class MaintenanceExporter extends Exporter
// {
//     protected static ?string $model = Maintenance::class;

//     public static function getColumns(): array
//     {
//         return [
//             ExportColumn::make('asset.name')->label('Asset'),
//             ExportColumn::make('submission_date')->label('Tanggal Pengajuan'),
//             ExportColumn::make('price')->label('Harga'),
//             ExportColumn::make('quantity')->label('Jumlah'),
//             ExportColumn::make('total')->label('Total'),
//         ];
//     }

//     public static function getCompletedNotificationBody(Export $export): string
//     {
//         $body = 'Your maintenance export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

//         if ($failedRowsCount = $export->getFailedRowsCount()) {
//             $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
//         }

//         return $body;
//     }
// }


class MaintenanceExporter implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Maintenance::with('asset')->get()->map(function ($item) {
            return [
                'Asset' => $item->asset->name,
                'Tanggal Pengajuan' => $item->submission_date,
                'Harga' => $item->price,
                'Jumlah' => $item->quantity,
                'Total' => $item->total,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Asset',
            'Tanggal Pengajuan',
            'Harga',
            'Jumlah',
            'Total',
        ];
    }
}