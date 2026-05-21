<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'value',
        'price_modifier',
        'stock',
        'color',
        'color_hex',
        'graduation',
        'graduation_type',
        'image_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_modifier' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByColor($query, string $color)
    {
        return $query->where('color', $color);
    }

    public function scopeByGraduation($query, string $graduation)
    {
        return $query->where('graduation', $graduation);
    }
}
