<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AssetCondition: string implements HasColor, HasIcon, HasLabel
{
    case New = 'new';
    case Used = 'used';
    case Damaged = 'damaged';

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'success',
            self::Used => 'primary',
            self::Damaged => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::New => 'heroicon-s-check-circle',
            self::Used => 'heroicon-o-clipboard-document-check',
            self::Damaged => 'heroicon-s-x-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Used => 'Used',
            self::Damaged => 'Damaged',
        };
    }
}
