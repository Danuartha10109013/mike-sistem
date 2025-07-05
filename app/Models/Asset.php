<?php

namespace App\Models;

use App\AssetCondition;
use App\MaintenanceStatus;
use App\PurchaseStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $casts = [
        'condition' => AssetCondition::class,
    ];

    public static function number()
    {
        $last = self::latest()->first();
        if (!$last) {
            return 'ASSET-' . date('Ymd') . '-' . '001';
        }
        $lastNumber = $last->number;
        $lastNumber = explode('-', $lastNumber);
        $lastNumber = end($lastNumber);
        $lastNumber = (int)$lastNumber;
        $lastNumber++;
        $lastNumber = str_pad($lastNumber, 3, '0', STR_PAD_LEFT);
        return 'ASSET-' . date('Ymd') . '-' . $lastNumber;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    // public function penyusutan(): BelongsTo
    // {
    //     return $this->belongsTo(Penyusutan::class);
    // }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class)->latest();
    }

    public function approvedPurchases(): HasMany
    {
        return $this->hasMany(Purchase::class)->latest()->where('status', PurchaseStatus::Completed);
    }

    public function approvedMaintenances(): HasMany
    {
        return $this->maintenances()->where('status', MaintenanceStatus::Completed)->latest();
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class)->latest();
    }

    public function scopeNew($query)
    {
        return $query->where('condition', 'new');
    }

    public function scopeUsed($query)
    {
        return $query->where('condition', 'used');
    }

    public function scopeDamaged($query)
    {
        return $query->where('condition', 'damaged');
    }
}
