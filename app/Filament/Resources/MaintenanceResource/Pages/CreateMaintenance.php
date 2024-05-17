<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Filament\Resources\MaintenanceResource;
use App\MaintenanceStatus;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenance extends CreateRecord
{
    protected static string $resource = MaintenanceResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        if (auth()->user()->isAdmin()) {
            $data['status'] = MaintenanceStatus::Approved;
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
            ->title('Maintenance created')
            ->body('The maintenance has been created successfully.');
    }
}
