<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SeoSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getForPage(string $pageKey): ?static
    {
        return static::where('page_key', $pageKey)
            ->where('is_active', true)
            ->first();
    }

    protected function ogImageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->og_image ? asset('storage/' . $this->og_image) : null);
    }

    protected function twitterImageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->twitter_image ? asset('storage/' . $this->twitter_image) : null);
    }
}
