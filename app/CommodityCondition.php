<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CommodityCondition: string implements HasColor, HasIcon, HasLabel
{
    case New = 'new';
    case Used = 'used';
    case Damaged = 'damaged';

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'green',
            self::Used => 'yellow',
            self::Damaged => 'red',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::New => 'heroicon-o-check-circle',
            self::Used => 'heroicon-m-at-symbol',
            self::Damaged => 'heroicon-m-sparkles',
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
