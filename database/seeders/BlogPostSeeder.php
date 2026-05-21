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
                'title' => '¿Cuántas horas frente a pantalla son dañinas para tus ojos?',
                'slug' => 'cuantas-horas-frente-a-pantalla-son-daninas',
                'excerpt' => 'La mayoría pasamos 8 o más horas mirando pantallas. Te explicamos cuándo el daño empieza a ser real y qué puedes hacer hoy para proteger tu visión.',
                'focus_keyword' => 'horas frente a pantalla',
                'meta_title' => '¿Cuántas horas frente a pantalla dañan tus ojos? | nuvion glass',
                'meta_description' => 'Descubre cuántas horas de pantalla son seguras, cuáles son los síntomas de daño visual y cómo proteger tus ojos con lentes de luz azul.',
                'author_name' => 'nuvion glass',
                'schema_type' => 'BlogPosting',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'content' => '<h2>El dato que nadie quiere escuchar</h2>
<p>Según el <strong>Vision Council</strong>, el adulto promedio pasa más de <strong>7 horas al día</strong> frente a una pantalla digital. Si sumamos el trabajo, el entretenimiento y el celular, muchos superamos las 10 horas fácilmente. Pero, ¿en qué momento el uso de pantallas empieza a dañar tu visión?</p>

<h2>¿A partir de cuántas horas hay riesgo real?</h2>
<p>La respuesta no es un número mágico, pero los estudios coinciden: <strong>a partir de las 2 horas continuas</strong> sin descanso, tu sistema visual empieza a resentirlo. El parpadeo se reduce hasta un 66%, los ojos se secan, y la musculatura ocular se fatiga por mantener el enfoque a corta distancia.</p>

<h3>Síntomas según las horas de exposición</h3>
<table>
<thead>
<tr><th>Horas de pantalla</th><th>Síntomas comunes</th></tr>
</thead>
<tbody>
<tr><td>0 – 2 horas</td><td>Generalmente sin molestias notables</td></tr>
<tr><td>2 – 4 horas</td><td>Ojos secos, leve fatiga visual</td></tr>
<tr><td>4 – 6 horas</td><td>Visión borrosa intermitente, tensión en la frente</td></tr>
<tr><td>6 – 8 horas</td><td>Dolor de cabeza, dificultad para enfocar, ojos rojos</td></tr>
<tr><td>+8 horas</td><td>Fatiga visual severa, insomnio, irritabilidad ocular</td></tr>
</tbody>
</table>

<h2>La regla 20-20-20: tu mejor aliada</h2>
<p>La Academia Americana de Oftalmología recomienda la <strong>regla 20-20-20</strong>: cada <strong>20 minutos</strong>, mira algo a <strong>20 pies de distancia</strong> (6 metros) durante <strong>20 segundos</strong>. Este simple hábito reduce significativamente la fatiga visual y ayuda a tus ojos a relajarse.</p>

<h2>¿Qué más puedes hacer?</h2>
<ul>
<li><strong>Ajusta el brillo</strong> de tu pantalla para que coincida con la luz ambiente.</li>
<li><strong>Aumenta el tamaño del texto</strong> para reducir el esfuerzo de enfoque.</li>
<li><strong>Parpadea conscientemente</strong>: suena raro, pero funciona.</li>
<li><strong>Usa lentes con filtro de luz azul</strong>: bloquean entre el 30-50% de la luz azul de alta energía que emiten las pantallas, reduciendo la fatiga y protegiendo la retina.</li>
</ul>

<h2>La solución que llevas puesta</h2>
<p>Los lentes con filtro de luz azul no son un lujo ni una moda: son una herramienta de prevención. Si pasas más de 4 horas al día frente a una pantalla (y probablemente es más), tus ojos necesitan protección activa.</p>
<p>En <strong>nuvion glass</strong> diseñamos lentes con filtro de luz azul que puedes usar todo el día: ligeros, con estilo, y sin necesidad de graduación.</p>',
            ],
            [
                'title' => 'Síntomas de fatiga visual digital: ¿los estás ignorando?',
                'slug' => 'sintomas-fatiga-visual-digital',
                'excerpt' => 'Ojos secos, visión borrosa, dolores de cabeza. Son señales que tu cuerpo manda y que muchos ignoran. Aprende a identificarlos y a tratarlos.',
                'focus_keyword' => 'fatiga visual digital síntomas',
                'meta_title' => 'Fatiga Visual Digital: 6 Síntomas que No Debes Ignorar | nuvion glass',
                'meta_description' => '¿Ojos cansados, secos o con visión borrosa? Conoce los 6 síntomas de fatiga visual digital y cómo prevenirlos con hábitos y lentes especializados.',
                'author_name' => 'nuvion glass',
                'schema_type' => 'BlogPosting',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'content' => '<h2>Tu cuerpo te está hablando. ¿Lo escuchas?</h2>
<p>La fatiga visual digital, también conocida como <strong>síndrome de visión por computadora (CVS)</strong>, afecta al <strong>90% de las personas</strong> que usan pantallas más de 3 horas al día. Lo peor es que muchos normalizan los síntomas y los confunden con cansancio general.</p>

<h2>Los 6 síntomas que no debes ignorar</h2>

<h3>1. Ojos secos e irritados</h3>
<p>Cuando miras una pantalla, <strong>parpadeas un 66% menos</strong> de lo normal. Esto reduce la capa de lágrima que protege tu córnea, provocando sequedad, ardor y sensación de "arenilla".</p>

<h3>2. Visión borrosa intermitente</h3>
<p>Después de horas de pantalla, tu ojo pierde capacidad de enfocar rápidamente. Puedes notar que la vista se nubla al mirar algo lejano, o que las letras se "mueven" ligeramente.</p>

<h3>3. Dolores de cabeza frecuentes</h3>
<p>La tensión en los músculos oculares se transmite a la frente, las sienes y la nuca. Si tus dolores de cabeza aparecen siempre después de trabajar en la computadora, no es coincidencia.</p>

<h3>4. Dolor en cuello y hombros</h3>
<p>Aunque no es directamente ocular, la fatiga visual provoca posturas compensatorias: inclinar la cabeza, acercarte a la pantalla, tensar los hombros. El dolor muscular es una extensión del problema visual.</p>

<h3>5. Sensibilidad a la luz</h3>
<p>La sobreexposición a la luz azul de las pantallas puede sensibilizar tus ojos a la luz en general. Si sales de la oficina y la luz del sol te molesta más de lo normal, la fatiga digital puede ser la causa.</p>

<h3>6. Dificultad para dormir</h3>
<p>La luz azul suprime la producción de <strong>melatonina</strong> (la hormona del sueño). Si usas pantallas antes de dormir, tu cerebro no recibe la señal de que es hora de descansar, provocando <strong>insomnio digital</strong>.</p>

<h2>¿Cuándo deberías ver a un especialista?</h2>
<p>Consulta a un oftalmólogo si:</p>
<ul>
<li>Los síntomas persisten incluso después de descansar</li>
<li>Notas cambios permanentes en tu visión</li>
<li>Tienes dolores de cabeza severos o frecuentes (más de 3 por semana)</li>
<li>Ves destellos o manchas flotantes</li>
</ul>

<h2>Hábitos que agravan la fatiga visual</h2>
<ul>
<li>Usar pantallas en la oscuridad (el contraste extremo fuerza al ojo)</li>
<li>No ajustar el brillo según la iluminación ambiente</li>
<li>Tener la pantalla demasiado cerca (menos de 50 cm)</li>
<li>No tomar descansos visuales en jornadas largas</li>
<li>Ignorar la necesidad de lentes de protección</li>
</ul>

<h2>Prevención activa: más que un hábito</h2>
<p>La regla 20-20-20, buena iluminación y descansos frecuentes son el primer paso. Pero si tu rutina diaria implica pantallas por horas, necesitas una barrera física: <strong>lentes con filtro de luz azul</strong> que reduzcan la cantidad de luz dañina que llega a tus ojos.</p>
<p>No esperes a que los síntomas se vuelvan crónicos. La prevención es siempre más barata que el tratamiento.</p>',
            ],
            [
                'title' => 'Lentes sin graduación con filtro azul: ¿realmente funcionan?',
                'slug' => 'lentes-sin-graduacion-filtro-azul-funcionan',
                'excerpt' => 'No necesitas mala vista para proteger tus ojos de las pantallas. Te explicamos cómo funcionan los lentes sin graduación con filtro de luz azul.',
                'focus_keyword' => 'lentes sin graduacion filtro azul',
                'meta_title' => 'Lentes Sin Graduación con Filtro Azul: ¿Funcionan? | nuvion glass',
                'meta_description' => 'Los lentes sin graduación con filtro de luz azul pueden reducir la fatiga visual. Descubre cómo funcionan y si son la solución para ti.',
                'author_name' => 'nuvion glass',
                'schema_type' => 'BlogPosting',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'content' => '<h2>La pregunta más frecuente que recibimos</h2>
<p>"Si no tengo problemas de vista, ¿para qué necesito lentes?" Es una pregunta válida. La respuesta corta: <strong>no necesitas mala vista para proteger tus ojos</strong>. Los lentes con filtro de luz azul sin graduación están diseñados para prevención, no para corrección.</p>

<h2>¿Cómo funciona el filtro de luz azul?</h2>
<p>Las pantallas digitales emiten luz en todo el espectro visible, pero la <strong>luz azul de alta energía (HEV)</strong>, entre 380 y 500 nanómetros, es la más problemática. Los lentes con filtro de luz azul tienen un recubrimiento especial que:</p>
<ul>
<li><strong>Refleja</strong> entre el 30-50% de la luz azul de alta energía</li>
<li><strong>Absorbe</strong> parte de la radiación antes de que llegue a la retina</li>
<li><strong>Reduce el deslumbramiento</strong> de las pantallas</li>
<li><strong>Mejora el contraste</strong> visual, haciendo que la pantalla se vea más cómoda</li>
</ul>

<h2>Lentes graduados vs. sin graduación</h2>
<table>
<thead>
<tr><th>Característica</th><th>Con graduación</th><th>Sin graduación</th></tr>
</thead>
<tbody>
<tr><td>Corrige defectos visuales</td><td>Sí</td><td>No</td></tr>
<tr><td>Filtro de luz azul</td><td>Opcional (se agrega al tratamiento)</td><td>Incluido de fábrica</td></tr>
<tr><td>Requiere receta médica</td><td>Sí</td><td>No</td></tr>
<tr><td>Precio promedio</td><td>$2,000 - $8,000 MXN</td><td>$499 - $999 MXN</td></tr>
<tr><td>Ideal para</td><td>Personas con miopía, astigmatismo, etc.</td><td>Cualquier persona frente a pantallas</td></tr>
</tbody>
</table>

<h2>¿Para quién son ideales?</h2>
<p>Los lentes sin graduación con filtro de luz azul son perfectos para:</p>
<ul>
<li><strong>Oficinistas y trabajadores remotos</strong> que pasan 6+ horas frente a la computadora</li>
<li><strong>Gamers</strong> en sesiones prolongadas de juego</li>
<li><strong>Estudiantes</strong> que usan tablets y laptops para estudiar</li>
<li><strong>Creadores de contenido</strong> que editan video o diseño por horas</li>
<li><strong>Cualquier persona</strong> que usa el celular antes de dormir</li>
</ul>

<h2>Preguntas frecuentes</h2>

<h3>¿Puedo usar lentes de filtro azul todo el día?</h3>
<p>Sí. Los lentes con filtro de luz azul no afectan tu visión normal. Puedes usarlos para conducir, caminar o estar en interiores sin ningún problema. El filtro solo actúa sobre la luz azul artificial.</p>

<h3>¿Cambian los colores de lo que veo?</h3>
<p>Los filtros de buena calidad (como los de nuvion glass) tienen un tinte imperceptible. No verás todo amarillo ni distorsionado. La diferencia es sutil pero tu confort mejora notablemente.</p>

<h3>¿Son solo un placebo?</h3>
<p>No. Estudios publicados en el <strong>American Journal of Ophthalmology</strong> demuestran que los filtros de luz azul reducen la fatiga visual y mejoran la calidad del sueño en personas expuestas a pantallas. No es una moda: es ciencia aplicada.</p>

<h3>¿Se pueden usar sobre lentes de contacto?</h3>
<p>Sí. Si usas lentes de contacto para corregir tu visión, puedes usar los lentes con filtro azul encima como una capa adicional de protección.</p>

<h2>La inversión más inteligente para tus ojos</h2>
<p>Tus ojos no se reemplazan. Si ya inviertes en una buena silla para tu espalda y un buen monitor para tu productividad, ¿por qué no invertir en protección para tu visión?</p>
<p>En <strong>nuvion glass</strong> ofrecemos lentes sin graduación con filtro de luz azul profesional, diseño moderno y precio accesible. Porque cuidar tus ojos no debería ser complicado ni caro.</p>',
            ],
        ];

        foreach ($articles as $article) {
            BlogPost::create($article);
        }
    }
}
