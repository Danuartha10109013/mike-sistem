<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{

    protected $casts = [
//        'condition' => CommodityCondition::class
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
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
