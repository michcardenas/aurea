# 🔵 PLAN MAESTRO DEFINITIVO — nuvion - glass · E-commerce
> Versión: 2.0 | Fecha: Febrero 2026 | Dev: Michael Cárdenas / MyTech Solutions

---

## 📌 CONTEXTO DEL PROYECTO

| Campo | Detalle |
|-------|---------|
| **Cliente** | nuvion - glass |
| **Propietario** | Alan Alberto Chavira Sandoval |
| **Producto** | 7 modelos de lentes con filtro de luz azul (miopía, lectura, sin graduación) |
| **Objetivo** | Tienda visual y persuasiva que eduque, convierta y se posicione en Google |
| **Tiempo de entrega** | 12–15 días hábiles |
| **Stack** | Laravel 12 + Blade + Alpine.js + TailwindCSS + MySQL |
| **Hosting** | Hostgator (cuenta existente de NUTRAES, dominio nuvionglass.com a contratar) |
| **Acceso hosting** | https://cliente.hostgator.mx/inicio |

---

## 🎨 IDENTIDAD VISUAL — BRANDBOOK OFICIAL

### Nombre de marca
- ✅ Correcto: `nuvion - glass` (siempre en minúsculas, con espacio-guión-espacio)
- ❌ Nunca: `Nuvion Glass`, `NUVION`, `NUVION GLASS`, `Nuvion-Glass`
- Significado: "Nueva Visión" (Nu = Nueva + Vion = Visión)
- Pronunciación: `[nu · vion]`

### Paleta de colores

| Nombre | Pantone | CMYK | RGB | HEX |
|--------|---------|------|-----|-----|
| Azul oscuro (primario) | PANTONE 294 C | 100, 85, 29, 23 | 0, 47, 109 | `#002F6D` |
| Azul medio (secundario) | PANTONE 279 C | 72, 37, 0, 0 | 58, 141, 222 | `#3A8DDE` |
| Negro | Process Black CP | 0, 0, 0, 100 | 0, 0, 0 | `#000000` |
| Fondo hero/dark | — | — | — | `#0A0E1A` |
| Fondo secciones claras | — | — | — | `#F4F6F9` |
| Texto principal | — | — | — | `#1A1A2E` |

### Tipografía

| Tipo | Fuente | Uso |
|------|--------|-----|
| Institucional | **Bai Jamjuree Regular** | Logo, titulares del hero |
| Corporativa | **IBM Plex Sans** (fallback DIN 2014) | UI, body, botones |

> Archivo local: `public/fonts/BaiJamjureeRegular.ttf`
> IBM Plex Sans → Google Fonts CDN

### Estilo visual definido (decisiones tomadas)
- **Hero y navbar:** fondo oscuro `#0A0E1A`, textos blancos, acentos `#3A8DDE`
- **Resto del sitio:** fondo claro `#F4F6F9`, textos `#1A1A2E`
- **Fotos de productos:** sobre fondo blanco/neutro → cards con fondo blanco
- **Botones primarios:** fondo `#002F6D`, texto blanco, border-radius 8px
- **Botones secundarios:** outline `#3A8DDE`
- **Cards:** fondo blanco, sombra sutil, hover con borde `#3A8DDE`
- **Estilo general:** premium, moderno, limpio — referencia marcas ópticas europeas

### Reglas de uso del logo
- El imagotipo (símbolo + wordmark) siempre en sus proporciones originales
- El símbolo circular solo se usa como favicon o ícono independiente
- ❌ Nunca rotar, deformar, cambiar colores ni alterar proporciones

---

## 🧠 BUYER PERSONA

**Perfil:** 20–45 años, usa pantallas 6+ horas diarias, con o sin graduación

**Objeciones a resolver:**
- "¿De verdad sirven los filtros de luz azul?"
- "¿Para qué los necesito si mis ojos están bien?"
- "¿Son cómodos para usar todo el día?"

**Motivaciones:**
- Fatiga visual al final del día
- Dolores de cabeza frecuentes
- Dificultad para dormir después de usar pantallas

**Mensajes clave:**
1. Protege tus ojos antes de que sea tarde
2. Ven mejor, duerme mejor, rinde más
3. Sin graduación o con ella — hay un nuvion para ti
4. Diseño moderno que querrás usar todo el día

---

## 🗂️ ESTRUCTURA DEL PROYECTO

```
nuvion-glass/
├── PLAN.md
├── README.md
├── public/
│   └── fonts/
│       └── BaiJamjureeRegular.ttf
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── StorefrontController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── BlogController.php
│   │   │   ├── LandingController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── ProductAdminController.php
│   │   │       ├── OrderAdminController.php
│   │   │       ├── BlogAdminController.php
│   │   │       └── LeadAdminController.php
│   │   └── Middleware/
│   │       └── IsAdmin.php
│   ├── Models/
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── ProductVariant.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Customer.php
│   │   ├── Lead.php
│   │   └── BlogPost.php
│   └── Services/
│       ├── CartService.php
│       ├── CheckoutService.php
│       └── SeoService.php
├── resources/
│   ├── css/
│   │   └── app.css                  ← variables brandbook aquí
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php        ← storefront
│       │   └── admin.blade.php      ← panel admin
│       ├── partials/
│       │   ├── navbar.blade.php
│       │   └── footer.blade.php
│       ├── storefront/
│       │   ├── home.blade.php
│       │   ├── products/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── cart.blade.php
│       │   ├── checkout.blade.php
│       │   ├── checkout-success.blade.php
│       │   ├── blog/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   └── pages/
│       │       ├── que-es-luz-azul.blade.php
│       │       └── landing.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── products/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── orders/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── leads/
│       │   │   └── index.blade.php
│       │   └── blog/
│       │       ├── index.blade.php
│       │       ├── create.blade.php
│       │       └── edit.blade.php
│       └── emails/
│           ├── order-confirmation.blade.php
│           └── lead-welcome.blade.php
├── routes/
│   ├── web.php
│   └── admin.php
└── database/
    ├── migrations/
    └── seeders/
```

---

## 🛣️ RUTAS COMPLETAS

### Storefront (`routes/web.php`)
```
GET  /                          → StorefrontController@home
GET  /lentes                    → ProductController@index
GET  /lentes/{slug}             → ProductController@show
GET  /carrito                   → CartController@index
POST /carrito/agregar           → CartController@add
POST /carrito/actualizar        → CartController@update
POST /carrito/eliminar          → CartController@remove
GET  /checkout                  → CheckoutController@index
POST /checkout/procesar         → CheckoutController@process
GET  /checkout/gracias          → CheckoutController@success
GET  /blog                      → BlogController@index
GET  /blog/{slug}               → BlogController@show
GET  /que-es-la-luz-azul        → StorefrontController@educativa
GET  /lentes-para-pantalla      → LandingController@pantallas
GET  /quiz                      → LandingController@quiz
POST /quiz/resultado            → LandingController@quizResult
POST /leads/capturar            → LeadController@store
```

### Admin (`routes/admin.php`) — middleware IsAdmin
```
GET  /admin                     → Admin\DashboardController@index
GET  /admin/productos           → Admin\ProductAdminController@index
GET  /admin/productos/crear     → Admin\ProductAdminController@create
POST /admin/productos           → Admin\ProductAdminController@store
GET  /admin/productos/{id}/editar → Admin\ProductAdminController@edit
PUT  /admin/productos/{id}      → Admin\ProductAdminController@update
DELETE /admin/productos/{id}    → Admin\ProductAdminController@destroy
GET  /admin/ordenes             → Admin\OrderAdminController@index
GET  /admin/ordenes/{id}        → Admin\OrderAdminController@show
PUT  /admin/ordenes/{id}/estado → Admin\OrderAdminController@updateStatus
GET  /admin/ordenes/exportar    → Admin\OrderAdminController@export
GET  /admin/blog                → Admin\BlogAdminController@index
GET  /admin/blog/crear          → Admin\BlogAdminController@create
POST /admin/blog                → Admin\BlogAdminController@store
GET  /admin/blog/{id}/editar    → Admin\BlogAdminController@edit
PUT  /admin/blog/{id}           → Admin\BlogAdminController@update
DELETE /admin/blog/{id}         → Admin\BlogAdminController@destroy
GET  /admin/leads               → Admin\LeadAdminController@index
GET  /admin/leads/exportar      → Admin\LeadAdminController@export
```

---

## 🚀 FASES DEL PROYECTO

---

### FASE 1 — Setup y arquitectura
**Duración:** Día 1

#### Tareas
- [ ] Instalar Laravel 12 limpio
- [ ] Configurar TailwindCSS + Alpine.js con Vite
- [ ] Crear variables CSS del brandbook en `app.css`:

```css
:root {
  --color-primary:    #002F6D;
  --color-secondary:  #3A8DDE;
  --color-black:      #000000;
  --color-bg-dark:    #0A0E1A;
  --color-bg-light:   #F4F6F9;
  --color-text:       #1A1A2E;
  --color-white:      #FFFFFF;
  --color-muted:      #6B7280;
  --font-brand:       'Bai Jamjuree', sans-serif;
  --font-body:        'IBM Plex Sans', sans-serif;
}
```

- [ ] Configurar fuente Bai Jamjuree desde `public/fonts/`
- [ ] Configurar IBM Plex Sans desde Google Fonts
- [ ] Crear `layouts/app.blade.php` con slots, vite, Alpine y fuentes
- [ ] Crear `layouts/admin.blade.php` limpio y funcional
- [ ] Crear `partials/navbar.blade.php` y `partials/footer.blade.php`
- [ ] Configurar `.env` con conexión a base de datos

**Entregable:** Proyecto Laravel corriendo en local con brandbook aplicado

---

### FASE 2 — Home y páginas del storefront
**Duración:** Día 2–4

#### Secciones del Home (en orden estricto)

1. **NAVBAR**
   - Logo nuvion - glass (símbolo + wordmark)
   - Menú: Inicio / Lentes / ¿Qué es la luz azul? / Blog
   - Ícono carrito con contador de items
   - CTA: "Ver lentes" → `/lentes`
   - Fondo: `#0A0E1A`, textos blancos
   - Mobile: hamburger menu con Alpine.js

2. **HERO** ← fondo `#0A0E1A`
   - Headline: "Protege tu visión. Rinde más."
   - Subheadline: "Lentes con filtro de luz azul para quienes viven frente a pantallas"
   - CTA primario: "Descubre tu lente" → `/lentes`
   - CTA secundario: "¿Qué es la luz azul?" → `/que-es-la-luz-azul`
   - Imagen producto hero (lado derecho, fondo transparente)
   - Badge flotante: "✓ Filtro luz azul en todos los modelos"

3. **TRUST BAR** ← fondo `#002F6D`
   - 4 íconos SVG en línea horizontal
   - "Envío gratis" / "Filtro certificado" / "Garantía 1 año" / "7 modelos disponibles"

4. **SECCIÓN EDUCATIVA** ← fondo `#F4F6F9`
   - Título: "¿Qué te está haciendo la luz azul?"
   - 3 columnas: ícono SVG + título + descripción
   - Fatiga visual / Dolores de cabeza / Insomnio

5. **VIDEO** ← fondo `#0A0E1A`
   - Título: "Mira cómo funciona el filtro"
   - Embed YouTube (iframe responsivo con overlay branded)
   - Subtítulo debajo del video

6. **PRODUCTOS DESTACADOS** ← fondo blanco
   - Grid 3 columnas (1 en mobile)
   - Card: imagen producto fondo blanco + badge categoría + nombre + precio + botón
   - "Ver todos los lentes" → `/lentes`

7. **BENEFICIOS** ← fondo `#F4F6F9`
   - 4 columnas con íconos SVG
   - "Ve sin fatiga" / "Duerme mejor" / "Diseño moderno" / "Con o sin graduación"

8. **COMPARATIVO VISUAL** ← fondo blanco
   - Tabla o cards lado a lado: Sin nuvion vs Con nuvion
   - Sin: fatiga, insomnio, dolor de cabeza
   - Con: protección, descanso, claridad visual

9. **TESTIMONIOS** ← fondo `#F4F6F9`
   - 3 cards con foto/avatar, nombre, ciudad, 5 estrellas, texto
   - Datos seeder incluidos

10. **FAQ ACCORDION** ← fondo blanco
    - Alpine.js x-show / x-transition
    - 5 preguntas:
      1. ¿Realmente funciona el filtro de luz azul?
      2. ¿Necesito tener graduación para comprar?
      3. ¿Cuánto tiempo tarda el envío?
      4. ¿Tienen garantía los lentes?
      5. ¿Cómo sé qué modelo es para mí?

11. **CTA FINAL** ← fondo `#002F6D`
    - Headline: "Empieza a proteger tu visión hoy"
    - Botón blanco: "Ver todos los lentes" → `/lentes`

12. **FOOTER** ← fondo `#0A0E1A`
    - Logo nuvion - glass
    - Links: Inicio / Lentes / Blog / Quiz / Privacidad
    - Redes sociales (íconos SVG)
    - "© 2026 nuvion - glass. Todos los derechos reservados."

#### Otras páginas storefront
- [ ] `products/index.blade.php` → catálogo con filtros (miopía, lectura, sin graduación)
- [ ] `products/show.blade.php` → ficha completa: galería, variantes, descripción, confianza, productos relacionados
- [ ] `cart.blade.php` → carrito lateral (slide-over con Alpine.js)
- [ ] `checkout.blade.php` → paso a paso: datos → envío → pago
- [ ] `checkout-success.blade.php` → confirmación con número de orden
- [ ] `que-es-luz-azul.blade.php` → página educativa con Q&A estructurado para SEO
- [ ] `blog/index.blade.php` → listado de artículos
- [ ] `blog/show.blade.php` → artículo individual

**Entregable:** Vistas Blade completas, responsivas, aprobadas por cliente

---

### FASE 3 — Base de datos y backend
**Duración:** Día 4–7

#### Migraciones

```php
// categories
id, name, slug, description, image, is_active, timestamps

// products
id, category_id, name, slug, short_description, description,
price, compare_price, sku, stock, images (JSON), 
is_active, is_featured, meta_title, meta_description, timestamps

// product_variants
id, product_id, name, value, price_modifier, stock, timestamps

// customers
id, name, email, phone, address, city, state, country,
zip_code, timestamps

// orders
id, customer_id, order_number, status (pending/processing/shipped/delivered/cancelled),
subtotal, shipping_cost, total, payment_method, payment_status,
shipping_address (JSON), notes, timestamps

// order_items
id, order_id, product_id, variant_id, product_name, qty,
unit_price, total_price, timestamps

// leads
id, name, email, phone, source, quiz_result, timestamps

// blog_posts
id, title, slug, excerpt, content, cover_image,
meta_title, meta_description, is_published, published_at, timestamps
```

#### Modelos y relaciones
- [ ] `Product` → belongsTo Category, hasMany ProductVariants
- [ ] `Category` → hasMany Products
- [ ] `Order` → belongsTo Customer, hasMany OrderItems
- [ ] `OrderItem` → belongsTo Order, belongsTo Product, belongsTo ProductVariant
- [ ] `Customer` → hasMany Orders

#### Servicios
- [ ] `CartService` → carrito en sesión (add, remove, update, clear, total)
- [ ] `CheckoutService` → crear orden, crear/buscar customer, vaciar carrito
- [ ] `SeoService` → generar meta tags y schema JSON-LD dinámicos

#### Seeders
- [ ] 3 categorías: Miopía / Lectura / Sin graduación
- [ ] 7 productos con imágenes placeholder, precios y variantes
- [ ] 3 artículos de blog publicados
- [ ] 3 testimonios en sesión o tabla propia
- [ ] 1 usuario admin: admin@nuvionglass.com / password

#### Autenticación admin
- [ ] Laravel Breeze o Auth manual con middleware `IsAdmin`
- [ ] Login en `/admin/login`
- [ ] Redirect a `/admin/dashboard` tras autenticarse

**Entregable:** BD funcional con seeders, modelos con relaciones probadas

---

### FASE 4 — Panel Admin (Blade clásico)
**Duración:** Día 7–10

#### Dashboard
- Ventas del día / semana / mes
- Órdenes pendientes (con acceso rápido)
- Leads nuevos
- Stock bajo (alertas)
- Gráfica simple de ventas (últimos 7 días)

#### Módulo Productos
- [ ] Listado con búsqueda, filtro por categoría, toggle activo/inactivo
- [ ] CRUD completo con subida múltiple de imágenes
- [ ] Gestión de variantes (graduación, modelo)
- [ ] Campos SEO por producto (meta title, meta description)
- [ ] Preview del producto desde el admin

#### Módulo Órdenes
- [ ] Listado con filtros: estado, fecha, búsqueda por cliente
- [ ] Vista detalle con todos los datos
- [ ] Cambio de estado con select
- [ ] Exportar a CSV

#### Módulo Blog
- [ ] CRUD artículos con editor de texto (textarea o TinyMCE si aplica)
- [ ] Campos SEO editables por artículo
- [ ] Toggle publicado/borrador
- [ ] Vista previa del artículo

#### Módulo Leads
- [ ] Listado con fuente (quiz, landing, footer)
- [ ] Exportar CSV
- [ ] Marcar como contactado

**Entregable:** Panel admin funcional y usable por el cliente

---

### FASE 5 — SEO técnico y AEO
**Duración:** Día 8–11 (paralelo a Fase 4)

#### Keywords objetivo
- Principales: "lentes luz azul", "lentes anti luz azul", "lentes para pantalla"
- Long tails: "lentes luz azul con graduación", "lentes para computadora sin receta", "gafas filtro azul miopía"

#### SEO técnico
- [ ] `SeoService` con meta tags dinámicos por página
- [ ] `<title>` + `<meta description>` + canonical en todas las páginas
- [ ] Open Graph (og:title, og:description, og:image) por página
- [ ] Twitter Cards
- [ ] `sitemap.xml` dinámico en `/sitemap.xml` (productos + blog + páginas estáticas)
- [ ] `robots.txt` en `/robots.txt`
- [ ] Imágenes en WebP + atributo `alt` descriptivo + lazy loading
- [ ] Breadcrumbs visibles en catálogo y fichas

#### Schema JSON-LD
- [ ] `Organization` en home
- [ ] `Product` en fichas de producto (nombre, precio, disponibilidad, imagen, descripción)
- [ ] `FAQPage` en home y página educativa
- [ ] `BreadcrumbList` en catálogo y fichas
- [ ] `Article` en artículos del blog
- [ ] `WebSite` con SearchAction en home

#### SEO para IA / AEO (Answer Engine Optimization)
- [ ] Página `/que-es-la-luz-azul` con contenido tipo Q&A directo y estructurado
- [ ] FAQs respondidas con la estructura exacta que esperan los featured snippets
- [ ] 3 artículos base en el blog como seeders:
  1. "¿Qué es la luz azul y cómo afecta tus ojos?"
  2. "5 señales de que necesitas lentes con filtro de luz azul"
  3. "Lentes de lectura vs lentes anti luz azul: diferencias clave"

**Entregable:** Sitemap activo, schema validado en Google Rich Results Test, meta tags en 100% de páginas

---

### FASE 6 — Landing Pages y quiz
**Duración:** Día 10–12

#### Landing 1 — Genérica
- URL: `/lentes-para-pantalla`
- Headline: "Lentes anti luz azul — Protege tus ojos desde hoy"
- Foco: conversión directa al catálogo
- Sin navbar completo → navbar simplificado
- CTA con UTM params para GA4: `?utm_source=...&utm_medium=...&utm_campaign=...`

#### Quiz — "¿Qué lentes son para ti?"
- URL: `/quiz`
- 4 preguntas con Alpine.js (sin recarga de página):
  1. ¿Cuántas horas al día usas pantallas?
  2. ¿Tienes graduación (miopía o necesitas lentes de lectura)?
  3. ¿Qué síntoma te molesta más? (fatiga / dolores de cabeza / insomnio)
  4. ¿Cuál es tu estilo preferido? (clásico / moderno / deportivo)
- Resultado: recomendación de modelo específico con imagen, precio y CTA
- Captura de lead al final (nombre + email) antes de mostrar resultado
- Lead guardado en tabla `leads` con `source = 'quiz'` y `quiz_result = '{modelo}'`

#### Gestión de landings desde admin
- [ ] El cliente puede editar headline, subheadline y CTA desde el panel admin
- [ ] Sin tocar código

**Entregable:** Landing + quiz funcionales con captura de leads

---

### FASE 7 — Correos transaccionales
**Duración:** Día 11–12 (paralelo)

- [ ] `order-confirmation.blade.php` → resumen de orden, datos de envío, soporte
- [ ] `lead-welcome.blade.php` → bienvenida al newsletter/quiz con código de descuento
- [ ] Configurar en `.env`: MAIL_MAILER, SMTP o Mailgun
- [ ] Probar envío real en staging

---

### FASE 8 — QA, pruebas y lanzamiento
**Duración:** Día 13–15

#### Checklist técnico
- [ ] Responsive verificado en: 375px / 768px / 1280px / 1920px
- [ ] Flujo completo: navegar → producto → carrito → checkout → confirmación → email
- [ ] Quiz completo: preguntas → resultado → captura lead → guardado en DB
- [ ] CRUD admin: crear, editar, eliminar producto y artículo
- [ ] Cambio de estado de órdenes funcionando
- [ ] Exportar CSV órdenes y leads
- [ ] Correos transaccionales enviándose
- [ ] Sin errores en consola del navegador
- [ ] PageSpeed Insights móvil ≥ 80

#### Checklist SEO
- [ ] Google Search Console conectado con sitemap enviado
- [ ] Sin errores 404 (verificar en consola Laravel)
- [ ] Schema válido en Google Rich Results Test
- [ ] Meta tags presentes en TODAS las páginas
- [ ] Open Graph correcto (verificar con Facebook Sharing Debugger)

#### Checklist de marca
- [ ] El nombre `nuvion - glass` escrito correctamente en todo el sitio
- [ ] Colores oficiales usados correctamente
- [ ] Tipografías aplicadas según brandbook
- [ ] Logo sin deformaciones ni rotaciones

#### Despliegue en Hostgator
- [ ] Subir proyecto vía Git o FTP
- [ ] Configurar PHP 8.2+
- [ ] Crear base de datos MySQL en cPanel
- [ ] Configurar `.env` de producción
- [ ] Ejecutar `php artisan migrate --seed` en producción
- [ ] Configurar dominio `nuvionglass.com` (DNS → Hostgator)
- [ ] Activar SSL (Let's Encrypt desde cPanel)
- [ ] Verificar `APP_ENV=production` y `APP_DEBUG=false`

**Entregable:** Tienda live, funcional, con SSL activo

---

## 📋 ENTREGABLES FINALES

| # | Entregable | Estado |
|---|-----------|--------|
| 1 | Tienda Laravel 12 live (storefront completo) | ⬜ |
| 2 | Panel admin Blade (productos, órdenes, leads, blog) | ⬜ |
| 3 | SEO base completo (metas, schema, sitemap) | ⬜ |
| 4 | Landing page + quiz con captura de leads | ⬜ |
| 5 | Correos transaccionales activos | ⬜ |
| 6 | Código fuente en repositorio Git | ⬜ |
| 7 | Capacitación 1h al cliente (en vivo o grabada) | ⬜ |
| 8 | Documentación de uso del panel admin | ⬜ |
| 9 | 15 días de soporte post-entrega | ⬜ |

---

## ⏳ TIMELINE

| Día | Actividad |
|-----|-----------|
| 1 | Setup Laravel + brandbook CSS + layouts + navbar + footer |
| 2–4 | Home completo + páginas storefront (catálogo, ficha, carrito, checkout) |
| 4–7 | Migraciones + modelos + servicios + seeders |
| 7–10 | Panel admin (productos, órdenes, leads, blog) |
| 8–11 | SEO técnico + schema JSON-LD + sitemap (paralelo) |
| 10–12 | Landing page + quiz + correos transaccionales |
| 13–15 | QA completo + ajustes + deploy en Hostgator + entrega |

---

## 🔑 PENDIENTES DEL CLIENTE

| Item | Estado |
|------|--------|
| Dominio nuvionglass.com contratado | ⬜ Pendiente |
| Fotos catálogo en alta resolución (7 modelos) | ✅ Entregadas |
| Pasarela de pago definida (Stripe / MercadoPago / PayU) | ⬜ Pendiente |
| URL del video de YouTube para embed | ⬜ Pendiente |
| Precios de los 7 modelos | ⬜ Pendiente |
| Variantes por modelo (graduaciones disponibles) | ⬜ Pendiente |
| Política de envío y tiempos de entrega | ⬜ Pendiente |
| Política de devoluciones y garantía | ⬜ Pendiente |
| Redes sociales para links en footer | ⬜ Pendiente |
| Cuenta Google Analytics 4 | ⬜ Pendiente |
| Cuenta Google Search Console | ⬜ Pendiente |
| Correo corporativo para envíos transaccionales | ⬜ Pendiente |



*Plan Maestro v2.0 — MyTech Solutions*
*Dev: Michael Cárdenas | Cliente: nuvion - glass | Febrero 2026*