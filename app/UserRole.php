<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasColor, HasIcon, HasLabel
{
    case Employee = 'employee';
    case Admin = 'admin';
    case User = 'user';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Employee => 'warning',
            self::Admin => 'success',
            self::User => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return $this === self::Admin ? 'heroicon-o-shield-check' : 'heroicon-o-user';
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Employee => 'Employee',
            self::Admin => 'Director',
            self::User => 'Admin',
        };
    }
}
