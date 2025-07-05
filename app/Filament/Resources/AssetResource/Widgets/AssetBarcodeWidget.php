<?php

namespace App\Filament\Resources\AssetResource\Widgets;

use Filament\Widgets\Widget;

class AssetBarcodeWidget extends Widget
{
    protected static string $view = 'filament.resources.asset-resource.widgets.asset-barcode-widget';
    
    public $record;

    public function mount()
    {
        $this->record = request()->route('record');
    }
}
