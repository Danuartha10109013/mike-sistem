<?php

namespace App\Filament\Exports;

use App\Models\Asset;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;



class AssetExporter implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Asset::with('room')->get()->map(function ($item) {

            return [
                'Number' => $item->number,
                'Asset' => $item->name,
                'Jumlah' => $item->quantity,
                'Harga' => $item->price,
                'Penurunan' => $item->decrease != 0 ?  $item->decrease . '%': ' -',
                'Tanggal' => $item->date,
                'Kondisi' => $item->condition->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Number',
            'Asset',
            'Jumlah',
            'Harga',
            'Penurunan',
            'Tanggal',
            'Kondisi',
        ];
    }
}