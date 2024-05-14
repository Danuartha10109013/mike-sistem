<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PurchaseStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Completed = 'completed';
    case Rejected = 'rejected';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Approved => 'heroicon-o-check-circle',
            self::Completed => 'heroicon-o-clipboard-check',
            self::Rejected => 'heroicon-m-x-mark',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Completed => 'Completed',
            self::Rejected => 'Rejected',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Approved => 'green',
            self::Completed => 'blue',
            self::Rejected => 'red',
        };
    }
}
