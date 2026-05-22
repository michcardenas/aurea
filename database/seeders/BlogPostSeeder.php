<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'El ritual de las 3 capas: cómo construir una rutina facial atemporal',
                'slug' => 'ritual-tres-capas-rutina-facial-atemporal',
                'excerpt' => 'Tónico, sérum y crema. Tres gestos cuidados que transforman cualquier piel sin complicaciones. Te enseñamos a estructurar tu ritual diario.',
                'focus_keyword' => 'rutina facial diaria',
                'meta_title' => 'Ritual de 3 capas: rutina facial diaria | Belleza Áurea',
                'meta_description' => 'Aprende a construir tu rutina facial perfecta con tres pasos clave: tónico, sérum y crema. Belleza natural y atemporal.',
                'author_name' => 'Belleza Áurea',
                'schema_type' => 'BlogPosting',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'content' => '<h2>Menos productos, más constancia</h2>
<p>La mejor rutina facial no es la más larga, sino la que puedes mantener todos los días. En Belleza Áurea creemos en el <strong>ritual de las 3 capas</strong>: tónico, sérum y crema. Tres pasos, dos minutos, resultados visibles en 28 días.</p>

<h2>Capa 1 — Tónico: el reset de tu piel</h2>
<p>Después de limpiar el rostro, la piel queda ligeramente desequilibrada. El <strong>Tónico Floral Reequilibrante</strong> con agua de rosas y niacinamida restaura el pH, refresca y prepara la piel para absorber lo que viene.</p>
<p><em>Aplicación:</em> dos pulsaciones en algodón reutilizable, pasa por todo el rostro con gestos ascendentes.</p>

<h2>Capa 2 — Sérum: el activo concentrado</h2>
<p>Aquí está la diferencia real. El <strong>Sérum Áureo</strong> con vitamina C estabilizada y rosa mosqueta concentra los activos que iluminan, unifican el tono y suavizan líneas finas. Es el paso más transformador de tu rutina.</p>
<p><em>Aplicación:</em> 3 gotas en la palma, presiona suavemente sobre el rostro y cuello. Espera 30 segundos antes del siguiente paso.</p>

<h2>Capa 3 — Crema: el sello protector</h2>
<p>La <strong>Crema Hidratante Botánica</strong> con manteca de karité y ácido hialurónico sella todo el ritual y mantiene la hidratación por 24 horas. Forma una capa ligera, no grasa, ideal incluso bajo maquillaje.</p>
<p><em>Aplicación:</em> una avellana de producto, distribuye con masaje circular ascendente.</p>

<h2>El cuarto paso opcional: aceite o mascarilla nocturna</h2>
<p>Una o dos noches por semana, intensifica con el <strong>Aceite Esencial de Rosa</strong> o la <strong>Mascarilla Nocturna de Oro</strong>. La piel descansada absorbe mejor estos tratamientos.</p>

<h2>Consejos para hacer del ritual un hábito</h2>
<ul>
  <li><strong>Hazlo a la misma hora</strong> cada mañana y noche. La consistencia gana a la perfección.</li>
  <li><strong>Crea un espacio bonito</strong>: una bandeja con tus productos, una vela, una toalla suave.</li>
  <li><strong>Tómate tiempo</strong>: dos minutos de cuidado real valen más que cinco minutos apurados.</li>
  <li><strong>Confía en los 28 días</strong>: la piel se renueva en ese ciclo. Antes no juzgues los resultados.</li>
</ul>

<h2>¿Por dónde empezar?</h2>
<p>Si nunca tuviste una rutina, comienza con el <strong>Ritual Esencial</strong>: los tres productos en formato regalo, con instrucciones detalladas. Tu piel notará la diferencia desde la primera semana.</p>',
            ],
            [
                'title' => '5 ingredientes botánicos que sí funcionan (y por qué)',
                'slug' => 'cinco-ingredientes-botanicos-que-si-funcionan',
                'excerpt' => 'Rosa mosqueta, niacinamida, ácido hialurónico, karité y vitamina C. La ciencia detrás de los activos botánicos que transforman tu piel.',
                'focus_keyword' => 'ingredientes botánicos skincare',
                'meta_title' => '5 ingredientes botánicos que funcionan en skincare | Belleza Áurea',
                'meta_description' => 'Conoce los 5 ingredientes botánicos con evidencia científica que sí transforman tu piel: rosa mosqueta, niacinamida, hialurónico, karité y vitamina C.',
                'author_name' => 'Belleza Áurea',
                'schema_type' => 'BlogPosting',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'content' => '<h2>Naturaleza con respaldo científico</h2>
<p>"Natural" no significa "efectivo". Por eso en Belleza Áurea seleccionamos solo activos botánicos cuya eficacia está respaldada por estudios in vitro y clínicos. Te contamos los cinco que están en la mayoría de nuestras fórmulas.</p>

<h2>1. Rosa mosqueta</h2>
<p>Aceite prensado en frío de la rosa <em>Rosa rubiginosa</em>. Rico en ácidos grasos esenciales (omega 3, 6 y 9) y vitamina A natural. <strong>Estimula la regeneración celular, suaviza cicatrices y mejora la elasticidad</strong>. Está en nuestro Sérum Áureo y Aceite Esencial de Rosa.</p>

<h2>2. Niacinamida (Vitamina B3)</h2>
<p>Aunque suena a laboratorio, la niacinamida es un activo derivado de plantas. Estudios publicados en el <em>Journal of Cosmetic Dermatology</em> muestran que en concentraciones de 4-5% <strong>minimiza poros, controla brillo, mejora la barrera cutánea y unifica el tono</strong>. Pilar de nuestro Tónico Floral.</p>

<h2>3. Ácido hialurónico vegetal</h2>
<p>Obtenido por fermentación de plantas (no animal). Molécula que retiene hasta 1000 veces su peso en agua, <strong>hidratando profundamente sin sensación grasa</strong>. Mantiene la piel rellena y suave todo el día. Activo clave de nuestra Crema Hidratante Botánica.</p>

<h2>4. Manteca de karité</h2>
<p>Extraída de la nuez del árbol <em>Vitellaria paradoxa</em> de África occidental. Rica en vitaminas A, E, F y ácidos grasos. <strong>Nutre, calma irritaciones y crea una barrera protectora natural</strong> contra agresiones ambientales.</p>

<h2>5. Vitamina C estabilizada</h2>
<p>La forma estable de la vitamina C (ascorbil glucósido o tetraisopalmitato) es uno de los antioxidantes más estudiados. <strong>Ilumina la piel, neutraliza radicales libres, estimula colágeno y reduce manchas</strong>. Concentración óptima entre 10-15%, como en nuestro Sérum Áureo.</p>

<h2>Lo que evitamos</h2>
<ul>
  <li><strong>Parabenos</strong>: conservantes con evidencia de disrupción hormonal.</li>
  <li><strong>Sulfatos agresivos</strong> (SLS, SLES): resecan e irritan la barrera cutánea.</li>
  <li><strong>Fragancias sintéticas</strong>: pueden generar sensibilidad. Usamos aceites esenciales en bajas dosis.</li>
  <li><strong>Aceites minerales</strong>: oclusivos sin aportar nutrición real.</li>
</ul>

<h2>Cómo leer una etiqueta</h2>
<p>Los ingredientes se listan de mayor a menor concentración. Si el activo "estrella" aparece al final, está en concentración decorativa. En Belleza Áurea siempre ves los activos clave en los <strong>primeros cinco lugares</strong> de la lista INCI.</p>',
            ],
            [
                'title' => 'Cómo elegir tu perfume signature: la guía áurea',
                'slug' => 'como-elegir-perfume-signature-guia',
                'excerpt' => 'Tu perfume es tu firma invisible. Te explicamos las familias olfativas, cómo probar un perfume correctamente y por qué Eau de Parfum Áurea funciona en cualquier ocasión.',
                'focus_keyword' => 'cómo elegir perfume',
                'meta_title' => 'Cómo elegir tu perfume signature | Belleza Áurea',
                'meta_description' => 'Guía completa para elegir tu perfume firma: familias olfativas, notas de salida, corazón y fondo. Tips para probarlo correctamente.',
                'author_name' => 'Belleza Áurea',
                'schema_type' => 'BlogPosting',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'content' => '<h2>Tu perfume habla antes que tú</h2>
<p>Un perfume bien elegido es una declaración silenciosa de quién eres. No se trata de seguir tendencias, sino de encontrar el aroma que te representa. Esta guía te ayuda a hacerlo con criterio.</p>

<h2>Las 7 familias olfativas (en simple)</h2>
<ol>
  <li><strong>Florales</strong>: rosa, jazmín, peonía. Femeninos, elegantes, atemporales.</li>
  <li><strong>Cítricos</strong>: bergamota, limón, mandarina. Frescos, energéticos, ideales para el día.</li>
  <li><strong>Orientales</strong>: vainilla, ámbar, especias. Cálidos, envolventes, perfectos de noche.</li>
  <li><strong>Amaderados</strong>: sándalo, cedro, vetiver. Sobrios, masculinos o unisex.</li>
  <li><strong>Chipres</strong>: musgo de roble, bergamota, pachuli. Sofisticados y misteriosos.</li>
  <li><strong>Fougère</strong>: lavanda, geranio, cumarina. Frescos y aromáticos.</li>
  <li><strong>Gourmand</strong>: caramelo, chocolate, café. Dulces, jóvenes, divertidos.</li>
</ol>

<h2>La pirámide olfativa: salida, corazón, fondo</h2>
<p>Todo buen perfume tiene tres fases:</p>
<ul>
  <li><strong>Notas de salida (0-15 min)</strong>: lo primero que hueles. Suelen ser cítricos o hierbas. Las más volátiles.</li>
  <li><strong>Notas de corazón (15 min - 3 h)</strong>: el alma del perfume. Florales, especias suaves.</li>
  <li><strong>Notas de fondo (3 h en adelante)</strong>: la base que persiste sobre tu piel. Maderas, ámbar, almizcle.</li>
</ul>
<p><em>El Eau de Parfum Áurea sigue exactamente esta estructura: salida cítrica de bergamota y mandarina, corazón floral de rosa y jazmín, fondo de vainilla y ámbar dorado. Una sinfonía pensada para durar 8-10 horas.</em></p>

<h2>Cómo probar un perfume correctamente</h2>
<ol>
  <li><strong>No huelas más de 3 fragancias por visita.</strong> Tu nariz se satura.</li>
  <li><strong>Aplica en la piel, no en la blotter</strong>: la química personal cambia todo.</li>
  <li><strong>Espera al menos 20 minutos</strong> antes de juzgar — sentirás las notas de corazón.</li>
  <li><strong>Pruébalo varios días</strong>: lo que ames en la tienda puede aburrirte en una semana, y viceversa.</li>
</ol>

<h2>Tips para que tu perfume dure más</h2>
<ul>
  <li>Aplica sobre <strong>piel hidratada</strong>: las moléculas se adhieren mejor a la piel con crema.</li>
  <li><strong>Pulsos calientes</strong>: muñecas, detrás de las orejas, base del cuello, hueco del codo.</li>
  <li><strong>No frotes</strong> las muñecas: rompes la estructura de las notas.</li>
  <li><strong>Capas</strong>: usa una crema con perfume neutro debajo para extender la duración.</li>
</ul>

<h2>¿Por qué Eau de Parfum Áurea funciona en cualquier ocasión?</h2>
<p>La fórmula combina frescura cítrica para el día, corazón floral para la tarde y fondo cálido para la noche. <strong>Es un perfume que evoluciona contigo durante el día</strong>, sin necesidad de tener tres botellas distintas.</p>
<p>Aroma elegante, no invasivo, atemporal. Pensado para mujeres que valoran la sutileza como forma de distinción.</p>',
            ],
        ];

        foreach ($articles as $article) {
            BlogPost::updateOrCreate(['slug' => $article['slug']], $article);
        }
    }
}
