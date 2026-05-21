<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'internal_code',
        'name',
        'slug',
        'description',
        'type',
        'price',
        'compare_price',
        'stock',
        'images',
        'meta_title',
        'meta_description',
        'is_active',
        'is_featured',
        'badge_2x1',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'images' => 'array',
            'type' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'badge_2x1' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (! $product->slug) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // ── Relations ──

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->whereJsonContains('type', $type);
    }

    /**
     * Check if this product has a given type.
     */
    public function hasType(string $type): bool
    {
        return in_array($type, $this->type ?? []);
    }

    /**
     * Check if this product has any of the given types.
     */
    public function hasAnyType(array $types): bool
    {
        return !empty(array_intersect($this->type ?? [], $types));
    }

    /**
     * True if there is any stock available (in product or in any active variant).
     */
    public function hasStock(): bool
    {
        $variantStock = $this->variants->where('is_active', true)->sum('stock');

        if ($this->variants->where('is_active', true)->count() > 0) {
            return $variantStock > 0;
        }

        return (int) $this->stock > 0;
    }

    /**
     * Total available stock (sum of active variants, or product stock if no variants).
     */
    public function availableStock(): int
    {
        if ($this->variants->where('is_active', true)->count() > 0) {
            return (int) $this->variants->where('is_active', true)->sum('stock');
        }

        return (int) $this->stock;
    }

    /**
     * Get the primary type label for display.
     */
    public function getTypeLabelsAttribute(): string
    {
        $labels = [
            'miopia' => 'Miopía',
            'lectura' => 'Lectura',
            'sin_graduacion' => 'Sin Graduación',
            'toallitas' => 'Toallitas',
        ];

        return collect($this->type ?? [])->map(fn ($t) => $labels[$t] ?? ucfirst($t))->join(' · ');
    }

    // ── Accessors ──

    public function getBadgeTextAttribute(): ?string
    {
        if (! $this->badge_2x1) {
            return null;
        }

        if (! $this->hasAnyType(['miopia', 'lectura', 'sin_graduacion'])) {
            return null;
        }

        return '2x1 · $' . number_format($this->price, 2) . ' c/u';
    }

    // ── 2x1 Logic ──

    /**
     * Calculate 2x1 discount for a collection of cart items.
     * Only applies to lens products (not toallitas/accesorio) with badge_2x1 = true.
     *
     * Each item should have: 'product' (Product), 'qty' (int), 'unit_price' (float).
     *
     * Returns: ['total' => float, 'free_items' => array, 'savings' => float]
     */
    public static function calculate2x1(Collection $items): array
    {
        // Expand items by quantity into individual units, only for eligible lenses
        $units = [];

        foreach ($items as $item) {
            $product = $item['product'];

            if (! $product->badge_2x1) {
                continue;
            }

            if (! $product->hasAnyType(['miopia', 'lectura', 'sin_graduacion'])) {
                continue;
            }

            for ($i = 0; $i < $item['qty']; $i++) {
                $units[] = [
                    'name' => $product->name,
                    'price' => (float) $item['unit_price'],
                ];
            }
        }

        if (empty($units)) {
            return ['total' => 0, 'free_items' => [], 'savings' => 0];
        }

        // Sort by price descending — the cheaper one in each pair is free
        usort($units, fn ($a, $b) => $b['price'] <=> $a['price']);

        $total = 0;
        $freeItems = [];
        $originalTotal = array_sum(array_column($units, 'price'));

        foreach ($units as $index => $unit) {
            if (($index + 1) % 2 === 0) {
                // Every second item is free
                $freeItems[] = $unit['name'];
            } else {
                $total += $unit['price'];
            }
        }

        return [
            'total' => $total,
            'free_items' => $freeItems,
            'savings' => $originalTotal - $total,
        ];
    }
}
