<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type_filter',
        'image',
        'sort_order',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Returns the configured product types this category links to,
     * as an array of cleaned strings. Empty if no filter is set.
     */
    public function typeFilterList(): array
    {
        if (empty($this->type_filter)) {
            return [];
        }
        return array_values(array_filter(array_map(
            fn ($t) => trim($t),
            explode(',', $this->type_filter)
        )));
    }
}
