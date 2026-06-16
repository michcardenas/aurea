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

    /** Relación al producto estrella elegido por el admin. */
    public function starProduct()
    {
        return $this->belongsTo(Product::class, 'star_product_id');
    }

    public static function getCurrent(): static
    {
        return static::where('is_active', true)->latest()->first()
            ?? static::create([]);
    }
}
