# Belleza Áurea

> Belleza natural, elegante y atemporal.

Ecommerce premium de cosmética y rituales de belleza. Construido sobre Laravel 12 + Tailwind 4 + Alpine + Stripe.

## Stack

- **Backend**: Laravel 12 (PHP 8.2+), MySQL/MariaDB
- **Frontend**: Tailwind CSS 4 con `@theme`, Alpine.js 3, Vite 7
- **Pagos**: Stripe
- **Identidad de marca**: ver [`BRAND.md`](BRAND.md)

## Setup local

```bash
# 1. Dependencias
composer install
npm install

# 2. Entorno
cp .env.example .env
php artisan key:generate

# 3. Base de datos (MariaDB/MySQL de XAMPP)
# Crear BD 'aurea' en localhost:3306 (root sin password)
php artisan migrate

# 4. Storage symlink
php artisan storage:link

# 5. Dev
npm run dev       # Vite watcher
php artisan serve # http://localhost:8000
```

## Paleta y tipografía

| Token   | Hex      | Uso                          |
|---------|----------|------------------------------|
| sage    | #A8B29A  | Acentos botánicos            |
| cream   | #F7F3ED  | Fondo principal              |
| gold    | #D9B56D  | CTAs y precios               |
| blush   | #E8D1C5  | Surfaces secundarias         |
| taupe   | #B8A999  | Texto secundario y bordes    |

- Titulares: **Playfair Display**
- Cuerpo: **Montserrat**

Detalle completo en `BRAND.md`.

## Estructura

- `app/Http/Controllers/` — Storefront, Cart, Checkout, Product, Blog, Lead, Landing, Sitemap, Admin
- `app/Models/` — Product, Category, Order, Customer, BlogPost, page settings, etc.
- `resources/views/storefront/` — vistas públicas
- `resources/views/admin/` — panel administrativo
- `routes/web.php` — rutas públicas
- `routes/admin.php` — rutas admin (login en `/admin/login`)

## Origen

Esqueleto inicial: `nuvion-glass` (ecommerce de lentes con luz azul). Aquí está adaptado a una tienda de belleza:
identidad visual (paleta, fuentes, logo) reemplazada; rutas y modelos intactos para reutilizar la lógica de catálogo,
carrito, checkout, blog, quiz, descuentos, envíos, panel admin.
