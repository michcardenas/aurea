<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingRate;
use App\Models\ShippingSetting;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * Get all cart items with product data.
     */
    public function getItems(): Collection
    {
        $cart = session(self::SESSION_KEY, []);

        if (empty($cart)) {
            return collect();
        }

        $productIds = collect($cart)->pluck('product_id')->unique();
        $variantIds = collect($cart)->pluck('variant_id')->filter()->unique();

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $variants = $variantIds->isNotEmpty()
            ? ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id')
            : collect();

        return collect($cart)->map(function ($item, $key) use ($products, $variants) {
            $product = $products->get($item['product_id']);

            if (! $product) {
                return null;
            }

            $variant = isset($item['variant_id']) ? $variants->get($item['variant_id']) : null;
            $unitPrice = $product->price + ($variant ? $variant->price_modifier : 0);

            return [
                'key' => $key,
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'product' => $product,
                'variant' => $variant,
                'qty' => $item['qty'],
                'unit_price' => $unitPrice,
                'total' => $unitPrice * $item['qty'],
            ];
        })->filter()->values();
    }

    /**
     * Add a product (optionally with variant) to the cart.
     */
    public function add(int $productId, int $qty = 1, ?int $variantId = null): void
    {
        $cart = session(self::SESSION_KEY, []);
        $key = $this->itemKey($productId, $variantId);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'qty' => $qty,
            ];
        }

        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(string $itemKey, int $qty): void
    {
        $cart = session(self::SESSION_KEY, []);

        if (! isset($cart[$itemKey])) {
            return;
        }

        if ($qty <= 0) {
            unset($cart[$itemKey]);
        } else {
            $cart[$itemKey]['qty'] = $qty;
        }

        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(string $itemKey): void
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$itemKey]);
        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Clear the entire cart.
     */
    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Get cart subtotal (before 2x1 discount).
     */
    public function getSubtotal(): float
    {
        return $this->getItems()->sum('total');
    }

    /**
     * Calculate 2x1 discount.
     * Only lens products (miopia, lectura, sin_graduacion) with badge_2x1 qualify.
     * Expands by qty, sorts by price desc, every 2nd unit is free.
     */
    public function calculate2x1(): array
    {
        $items = $this->getItems();

        // Expand each item into individual units (only eligible lenses)
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
            return ['discount' => 0, 'free_items' => []];
        }

        // Sort by price descending — cheaper one in each pair is free
        usort($units, fn ($a, $b) => $b['price'] <=> $a['price']);

        $discount = 0;
        $freeItems = [];

        foreach ($units as $index => $unit) {
            if (($index + 1) % 2 === 0) {
                $discount += $unit['price'];
                $freeItems[] = $unit['name'];
            }
        }

        return ['discount' => $discount, 'free_items' => $freeItems];
    }

    /**
     * Get shipping cost based on configured rates.
     * El umbral de envío gratis se compara contra el TOTAL final (subtotal
     * menos 2×1 menos cupón), no contra el subtotal bruto. Asi el cliente
     * solo recibe envio gratis si lo que efectivamente esta pagando por
     * los productos supera el umbral configurado.
     *
     * @param string|null $state           The Mexican state for state-based rates
     * @param float       $couponDiscount  Coupon discount amount to subtract from the subtotal
     */
    public function getShipping(?string $state = null, float $couponDiscount = 0): float
    {
        $subtotal = $this->getSubtotal();
        $discount2x1 = $this->calculate2x1()['discount'] ?? 0;
        $effectiveTotal = max(0, $subtotal - $discount2x1 - $couponDiscount);

        $threshold = (float) ShippingSetting::get('free_shipping_threshold', 0);

        if ($threshold > 0 && $effectiveTotal >= $threshold) {
            return 0;
        }

        if ($state) {
            $rate = ShippingRate::findForState($state);
            if ($rate) {
                return (float) $rate->price;
            }
        }

        return (float) ShippingSetting::get('default_price', 99.00);
    }

    /**
     * Get cart total (subtotal + shipping).
     */
    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getShipping();
    }

    /**
     * Get total item count.
     */
    public function count(): int
    {
        $cart = session(self::SESSION_KEY, []);

        return array_sum(array_column($cart, 'qty'));
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(): bool
    {
        return empty(session(self::SESSION_KEY, []));
    }

    /**
     * Generate a unique key for a product+variant combination.
     */
    private function itemKey(int $productId, ?int $variantId): string
    {
        return $variantId ? "{$productId}_{$variantId}" : (string) $productId;
    }
}
