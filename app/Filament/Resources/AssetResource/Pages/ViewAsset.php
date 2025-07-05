<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Models\Asset;
use App\Models\Maintenance;
// use Filament\Pages\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
            
            Action::make('edit status')
            ->label('Edit Status')
                ->label('Edit Status')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->form([
                        Select::make('condition')
                            ->label('Asset Condition')
                            ->options([
                                'new' => 'New',
                                'used' => 'Used',
                                'damaged' => 'Damaged',
                            ])
                            ->required()
                            ->reactive(), // penting agar perubahan terdeteksi secara real-time

                        TextInput::make('price')
                            ->type('number')
                            ->label('Price')
                            ->visible(fn ($get) => $get('condition') === 'damaged')
                            ->required(fn ($get) => $get('condition') === 'damaged')
                            ->default(fn ($record) => Maintenance::where('asset_id', $record->id)->value('price') ?? ''),

                       TextInput::make('notes')
                        ->label('Detail Maintenance')
                        ->maxLength(255)
                        ->visible(fn ($get) => $get('condition') === 'damaged')
                        ->required(fn ($get) => $get('condition') === 'damaged')
                        ->default(fn ($record) => Maintenance::where('asset_id', $record->id)->value('notes') ?? '')
                        ,

                    ])
                    ->action(function (array $data, Asset $record) {
                        $record->condition = $data['condition'];
                        $record->save();

                        // Tambahkan ke table maintenance jika condition = damaged
                        if ($data['condition'] === 'damaged') {
                            $ada = Maintenance::where('asset_id',$record->id)->first();
                            if($ada){
                                $maintenance = Maintenance::where('asset_id',$record->id)->first();
                            }else{
                                $maintenance = new \App\Models\Maintenance();
                            }
                            $maintenance->asset_id = $record->id;
                            $maintenance->user_id = auth()->id();
                            $maintenance->quantity = 1;
                            $maintenance->price = $data['price'] ?? 0;
                            $maintenance->total = $data['price'] ?? 0;
                            $maintenance->submission_date = now();
                            $maintenance->status = 'pending';
                            $maintenance->notes = $data['notes'] ?? null;
                            $maintenance->save();
                        }

                        Notification::make()
                            ->success()
                            ->title('Asset condition updated')
                            ->send();
                    }),
                Action::make('Cetak Label')
                    ->url(fn ($record) => route('asset.label', $record->id))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer'),
        ];
    }
}
