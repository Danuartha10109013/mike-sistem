<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function penyusutan(): BelongsTo
    {
        return $this->belongsTo(Penyusutan::class);
    }
}
