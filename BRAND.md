# Belleza Áurea — Identidad de marca

> "Belleza natural, elegante y atemporal."

Fuente original: `Belleza Área identidad de marca (1).pdf` (entregado por el cliente).
Renders del manual en `brand_pages/`.

---

## 1. Paleta de colores

| Nombre          | Hex      | Uso sugerido                                         |
|-----------------|----------|------------------------------------------------------|
| Sage green      | #A8B29A  | Acentos botánicos, badges, hover de enlaces sutiles  |
| Soft cream      | #F7F3ED  | Fondo principal del sitio (body)                     |
| Champagne gold  | #D9B56D  | CTAs primarios, precios destacados, iconografía premium |
| Nude blush      | #E8D1C5  | Fondos de secciones, cards, surfaces secundarias     |
| Warm taupe      | #B8A999  | Texto secundario, bordes suaves, separadores         |

Sugerencia de neutros complementarios (no en el manual, pero necesarios para UI):
- Texto principal: `#2E2A26` (taupe muy oscuro, no negro puro)
- Líneas / dividers: `rgba(184,169,153,0.25)`

## 2. Tipografía

| Rol              | Familia          | Peso recomendado | Notas                          |
|------------------|------------------|------------------|--------------------------------|
| Titulares (H1–H3)| Playfair Display | 500 / 600        | Serif elegante, kerning amplio |
| Cuerpo / UI      | Montserrat       | 400 / 500        | Sans humanista                 |

Cargar vía Google Fonts:
```
https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Montserrat:wght@300;400;500;600&display=swap
```

## 3. Atributos de marca

Premium · Natural · Sofisticada · Delicada · Atemporal

## 4. Lineamientos visuales

- Fondo dominante: Soft cream (`#F7F3ED`), evitar blanco puro.
- CTAs: fondo Champagne gold, texto blanco crema; hover oscurece ~10%.
- Botones secundarios: borde Warm taupe 1px, texto Sage green.
- Imágenes de producto: sobre fondos crema o blush, con elementos botánicos sutiles.
- Tipografía de héroes: Playfair Display, tracking ligero (letter-spacing ~0.02em).
- Espaciado generoso (whitespace), líneas finas, divisores con motivo botánico opcional.

## 5. Logo

- Logotipo principal: monograma "BA" en oro con perfil femenino y gota dorada, dentro de aro y follaje.
- Monograma alterno: "BA" sin follaje para usos pequeños (favicon, app icon, watermark).
- Archivos pendientes de entregar por el cliente → almacenar en `public/img/brand/`:
  - `logo-principal.svg` / `.png`
  - `monograma.svg` / `.png`
  - `favicon.ico` / `favicon.svg`
