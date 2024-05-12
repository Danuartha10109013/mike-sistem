<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('username')
                ->label('Username')
                ->maxLength(255)
                ->unique('users', 'username', ignoreRecord: true)
                ->required(),
            $this->getNameFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent()
        ]);
    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Profile updated successfully!')
            ->body('Your profile has been updated successfully.');
    }
}
