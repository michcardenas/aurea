<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'category_cards' => 'array',
        'wipes_features' => 'array',
        'faqs' => 'array',
        'trust_badges' => 'array',
        'cta_trust_items' => 'array',
        'benefits_cards' => 'array',
        'comparison_without_items' => 'array',
        'comparison_with_items' => 'array',
    ];

    public static function getCurrent(): static
    {
        return static::where('is_active', true)->latest()->first()
            ?? static::create([]);
    }
}
