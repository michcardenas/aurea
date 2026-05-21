<?php

namespace App\Helpers;

class ColorHelper
{
    private static array $colors = [
        'Azul' => '#4A90D9',
        'Blanco' => '#F5F5F5',
        'Café' => '#795548',
        'Gris' => '#9E9E9E',
        'Negro' => '#212121',
        'Rosa' => '#F48FB1',
        'Verde' => '#4CAF50',
        'Arena Oscuro' => '#A1887F',
        'Cristal' => '#E3F2FD',
        'Morado' => '#7B1FA2',
        'Rojo' => '#E53935',
        'Amarillo' => '#FDD835',
        'Jaspe' => '#8D6E63',
    ];

    public static function hex(string $colorName): string
    {
        return self::$colors[$colorName] ?? '#CCCCCC';
    }

    public static function all(): array
    {
        return self::$colors;
    }
}
