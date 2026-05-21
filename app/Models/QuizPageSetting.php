<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizPageSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'recommendation_rules' => 'array',
        'questions' => 'array',
    ];

    public static function getCurrent(): static
    {
        return static::where('is_active', true)->latest()->first()
            ?? static::create([]);
    }

    /**
     * Get configured questions or default ones if none are set.
     */
    public function getQuestionsOrDefault(): array
    {
        return !empty($this->questions) ? $this->questions : self::defaultQuestions();
    }

    /**
     * Default quiz questions (used when admin hasn't configured any).
     */
    public static function defaultQuestions(): array
    {
        return [
            [
                'key' => 'usage',
                'label' => '¿Para qué usarás tus lentes?',
                'subtitle' => 'Selecciona tu uso principal.',
                'options' => [
                    ['value' => 'screen', 'label' => 'Trabajo / Oficina', 'desc' => 'Computadora, laptop, reuniones virtuales'],
                    ['value' => 'gaming', 'label' => 'Gaming', 'desc' => 'Sesiones largas de videojuegos'],
                    ['value' => 'study', 'label' => 'Estudio', 'desc' => 'Clases online, lectura digital'],
                    ['value' => 'general', 'label' => 'Uso general', 'desc' => 'Celular, tablet, TV'],
                ],
            ],
            [
                'key' => 'hours',
                'label' => '¿Cuántas horas al día usas pantallas?',
                'subtitle' => 'Incluye trabajo, estudio y tiempo libre.',
                'options' => [
                    ['value' => '1-3', 'label' => 'Menos de 4 horas', 'desc' => 'Uso moderado de pantallas'],
                    ['value' => '4-6', 'label' => '4 a 6 horas', 'desc' => 'Uso frecuente, trabajo o estudio'],
                    ['value' => '6-8', 'label' => '6 a 8 horas', 'desc' => 'Uso intenso, jornada completa'],
                    ['value' => '8+', 'label' => 'Más de 8 horas', 'desc' => 'Uso extremo, trabajo + ocio'],
                ],
            ],
            [
                'key' => 'prescription',
                'label' => '¿Necesitas graduación?',
                'subtitle' => 'Si usas lentes con receta, podemos integrar tu graduación.',
                'options' => [
                    ['value' => 'yes', 'label' => 'Sí, tengo graduación', 'desc' => 'Uso lentes con receta médica'],
                    ['value' => 'no', 'label' => 'No, sin graduación', 'desc' => 'Solo protección de luz azul'],
                ],
            ],
            [
                'key' => 'style',
                'label' => '¿Qué estilo prefieres?',
                'subtitle' => 'Elige el diseño que mejor refleje tu personalidad.',
                'options' => [
                    ['value' => 'classic', 'label' => 'Clásico', 'desc' => 'Líneas atemporales, versátil'],
                    ['value' => 'modern', 'label' => 'Moderno', 'desc' => 'Minimalista, contemporáneo'],
                    ['value' => 'sport', 'label' => 'Deportivo', 'desc' => 'Atlético, dinámico'],
                    ['value' => 'round', 'label' => 'Redondo', 'desc' => 'Retro, intelectual'],
                ],
            ],
        ];
    }
}
