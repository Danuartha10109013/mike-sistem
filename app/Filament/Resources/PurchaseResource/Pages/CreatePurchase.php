<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use App\Filament\Resources\PurchaseResource;
use App\PurchaseStatus;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
    
        $asset = \App\Models\Asset::create([
            'name' => $data['asset_name'],
            'brand_id' => $data['brand_id'],
            'category_id' => $data['category_id'],
            'room_id' => $data['room_id'] ?? NULL,
            'quantity' => 0,
            'price' => $data['price'],
            // 'penyusutan_id' => $data['penyusutan_id'],
            'condition' => 'new',
            'date' => $data['submission_date'],
            'user_id' => $data['user_id'] ?? NULL,
            'number' => \App\Models\Asset::number(),
        ]);
        $data['asset_id'] = $asset->id;
        $data['total'] = ((float) $data['price']) * ((float) ($data['quantity'] ?? 1));

        unset(
            $data['asset_name'],
            $data['brand_id'],
            $data['category_id'],
            $data['room_id'],
            $data['penyusutan_id'],
        );

        $data['user_id'] = auth()->id();
        if (auth()->user()->isAdmin()) {
            $data['status'] = PurchaseStatus::Approved;
            $data['approved_by'] = auth()->id();
            $data['approval_date'] = now();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Purchase created')
            ->body('The purchase has been created successfully.');
    }
}
