<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPageSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getCurrent(): static
    {
        return static::where('is_active', true)->latest()->first()
            ?? static::create([]);
    }

    /**
     * Cache de URL WhatsApp por request para evitar reconsultar el modelo
     * cada vez que el blade lo invoca.
     */
    private static ?string $cachedWhatsappUrl = null;

    /**
     * URL completa de WhatsApp para botones flotantes/CTAs.
     * Construida a partir de whatsapp_number y whatsapp_message del admin,
     * con fallback a los valores históricos si están vacíos.
     */
    public static function whatsappUrl(): string
    {
        if (self::$cachedWhatsappUrl !== null) {
            return self::$cachedWhatsappUrl;
        }

        $page = static::getCurrent();

        $number = preg_replace('/\D/', '', $page->whatsapp_number ?? '');
        if ($number === '') {
            $number = '528146964477';
        }

        $message = trim((string) ($page->whatsapp_message ?? ''));
        if ($message === '') {
            $message = 'Hola, me interesa información sobre los lentes Nuvion Glass';
        }

        return self::$cachedWhatsappUrl = 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
    }
}
