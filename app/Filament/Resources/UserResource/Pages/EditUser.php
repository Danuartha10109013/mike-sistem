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
                ->label(__('user.column.name'))
                ->placeholder(__('user.placeholder.name'))
                ->required(),
            TextInput::make('username')
                ->label(__('user.column.username'))
                ->placeholder(__('user.placeholder.username'))
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('password')
                ->label(__('user.placeholder.password'))
                ->placeholder(__('user.placeholder.password'))
                ->password()
                ->autocomplete('new-password')
                ->confirmed()
                ->nullable(),
            TextInput::make('password_confirmation')
                ->label(__('user.placeholder.confirm_password'))
                ->placeholder(__('user.placeholder.confirm_password'))
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
