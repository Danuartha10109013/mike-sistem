<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Name')
                ->required(),
            TextInput::make('username')
                ->label('Username')
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->autocomplete('new-password')
                ->confirmed()
                ->nullable(),
            TextInput::make('password_confirmation')
                ->label('Confirm Password')
                ->password()
                ->autocomplete('new-password')
                ->nullable()
                ->dehydrated(false)
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('User Updated')
            ->body('The user was updated successfully.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['password'] === null) {
            unset($data['password']);
        }

        return $data;
    }
}
