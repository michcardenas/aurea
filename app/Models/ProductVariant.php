<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Variante genérica de producto.
 *
 * `option_type` define el tipo (color/size/scent/finish/...) y dirige cómo el
 * admin captura el dato y cómo el storefront lo renderiza (swatches para
 * color, pills para el resto).
 *
 * Campos clave:
 *   option_type      → categoría del atributo (enum)
 *   name             → etiqueta visible ('Tono', 'Tamaño', 'Aroma', ...)
 *   value            → valor visible ('Rojo Coral', '50 ml', 'Vainilla', ...)
 *   color_hex        → solo para option_type='color' (swatch)
 *   image_path       → imagen específica de esta variante (opcional)
 *   price_modifier   → +/- al precio base del producto
 *   stock            → inventario propio de esta variante
 *   is_active        → visible para el cliente
 *
 * Legacy: `color`, `graduation`, `graduation_type` se mantienen por
 * compatibilidad con el esqueleto anterior (lentes) pero ya no son
 * necesarios para productos nuevos.
 */
class ProductVariant extends Model
{
    public const OPTION_TYPES = [
        'color'    => 'Color',
        'size'     => 'Tamaño',
        'scent'    => 'Aroma',
        'finish'   => 'Acabado',
        'style'    => 'Estilo',
        'material' => 'Material',
        'quantity' => 'Cantidad',
        'other'    => 'Otro',
    ];

    /**
     * Etiqueta por defecto para cada tipo (usada en el form admin si el
     * usuario no captura `name`).
     */
    public const DEFAULT_LABELS = [
        'color'    => 'Tono',
        'size'     => 'Tamaño',
        'scent'    => 'Aroma',
        'finish'   => 'Acabado',
        'style'    => 'Estilo',
        'material' => 'Material',
        'quantity' => 'Cantidad',
        'other'    => 'Variante',
    ];

    protected $fillable = [
        'product_id',
        'option_type',
        'name',
        'value',
        'color',           // legacy
        'color_hex',
        'graduation',      // legacy
        'graduation_type', // legacy
        'image_path',
        'price_modifier',
        'stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_modifier' => 'decimal:2',
            'is_active'      => 'boolean',
            'stock'          => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('option_type', $type);
    }

    // ── Helpers ──

    /**
     * Etiqueta humana para el tipo de variante.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::OPTION_TYPES[$this->option_type] ?? 'Variante';
    }

    /**
     * ¿Esta variante usa color picker?
     */
    public function getIsColorAttribute(): bool
    {
        return $this->option_type === 'color';
    }
}
