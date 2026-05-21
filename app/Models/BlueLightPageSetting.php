<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlueLightPageSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active'             => 'boolean',
        'symptoms_cards'        => 'array',
        'protection_benefits'   => 'array',
        'profiles_cards'        => 'array',
        'faqs'                  => 'array',
        'compare_without_items' => 'array',
        'compare_with_items'    => 'array',
        'compare_metrics'       => 'array',
    ];

    public static function getCurrent(): static
    {
        return static::where('is_active', true)->latest()->first()
            ?? static::create([]);
    }
}
