<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
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
}
