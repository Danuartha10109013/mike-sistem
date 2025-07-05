<x-filament::widget>
    <x-filament::card>
        <div class="text-center">
            <h2 class="text-lg font-bold mb-2">Barcode Aset</h2>
            {!! DNS1D::getBarcodeHTML($record->kode_asset, 'C128') !!}
            <p class="mt-2 text-sm text-gray-600">{{ $record->kode_asset }}</p>
        </div>
    </x-filament::card>
</x-filament::widget>
