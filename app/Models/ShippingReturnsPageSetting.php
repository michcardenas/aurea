<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingReturnsPageSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getCurrent(): static
    {
        return static::where('is_active', true)->latest()->first()
            ?? static::create([]);
    }
}
