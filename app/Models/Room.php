<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    /**
     * Get the commodities for the room.
     *
     * @return HasMany
     */
    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class);
    }
}
