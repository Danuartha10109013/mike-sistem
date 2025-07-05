<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
// use Filament\Pages\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Milon\Barcode\DNS2D;


class ViewAsset extends ViewRecord
{
    protected static string $resource = AssetResource::class;

    public static function getNavigationLabel(): string
    {
        return __('asset.navigation.view_asset');
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         \App\Filament\Widgets\AssetBarcodeWidget::class,
    //     ];
    // }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Cetak Label')
                ->url(fn ($record) => route('asset.label', $record->id))
                ->openUrlInNewTab()
                ->icon('heroicon-o-printer'),
        ];
    }
}
