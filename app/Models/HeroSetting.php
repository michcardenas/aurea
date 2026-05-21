<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'trust_items' => 'array',
        'hero_images' => 'array',
        'overlay_opacity' => 'float',
    ];

    public static function getCurrent(): static
    {
        return static::where('is_active', true)->latest()->first()
            ?? static::create([]);
    }
}
