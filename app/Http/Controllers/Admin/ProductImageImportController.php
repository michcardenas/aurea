<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

/**
 * Importador masivo de imágenes de producto vía ZIP.
 *
 * Convención de nombres dentro del ZIP:
 *   {referencia}.{ext}              → única imagen del producto
 *   {referencia}-1.{ext}            → primera imagen (galería)
 *   {referencia}-2.{ext}            → segunda imagen
 *   ...
 *
 * Ejemplos:
 *   13.jpg          → producto con internal_code "13"
 *   1004-B.jpg      → producto con internal_code "1004-B" (NO se confunde con sufijo
 *                     porque la B no es un dígito puro al final)
 *   1307-1.jpg      → producto "1307", imagen 1
 *   1307-2.jpg      → producto "1307", imagen 2
 *
 * Procesamiento por imagen:
 *   1. Match con producto por internal_code.
 *   2. Redimensiona a max 1200px (mantiene aspect ratio).
 *   3. Convierte a WebP calidad 85.
 *   4. Guarda en storage/app/public/products/{slug}/{idx}.webp.
 *   5. Actualiza Product->images[] (array).
 */
class ProductImageImportController extends Controller
{
    /** Tamaño máximo en píxeles (lado largo) */
    const MAX_DIM = 1200;

    /** Calidad WebP (0-100) */
    const WEBP_QUALITY = 85;

    /** Extensiones aceptadas */
    const VALID_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    public function show(\Illuminate\Http\Request $request)
    {
        // Productos activos sin imágenes — paginados 10 por página
        $query = \App\Models\Product::with('category')
            ->where('is_active', true)
            ->whereRaw('(images IS NULL OR JSON_LENGTH(images) = 0)');

        if ($search = trim((string) $request->input('q', ''))) {
            $query->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('internal_code', 'like', "%{$search}%");
            });
        }

        if ($catFilter = $request->input('cat')) {
            $query->where('category_id', $catFilter);
        }

        $missingProducts = $query
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Conteos para los stats del header
        $missingTotal = \App\Models\Product::where('is_active', true)
            ->whereRaw('(images IS NULL OR JSON_LENGTH(images) = 0)')
            ->count();
        $totalActive = \App\Models\Product::where('is_active', true)->count();

        // Categorías con conteo de faltantes (para filtro)
        $categoriesWithMissing = \App\Models\Category::select('categories.*')
            ->selectRaw('(SELECT COUNT(*) FROM products WHERE products.category_id = categories.id AND products.is_active = 1 AND (products.images IS NULL OR JSON_LENGTH(products.images) = 0)) AS missing_count')
            ->orderBy('name')
            ->get()
            ->filter(fn ($c) => $c->missing_count > 0)
            ->values();

        return view('admin.products.import-images', compact(
            'missingProducts', 'missingTotal', 'totalActive', 'categoriesWithMissing'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        @ini_set('memory_limit', '1024M');
        @ini_set('max_execution_time', '900');
        set_time_limit(900);

        $request->validate([
            'file' => 'required|file|mimes:zip|max:512000', // 500 MB
            'mode' => 'required|in:replace,append',
        ]);

        if (! class_exists(ZipArchive::class)) {
            return back()->with('error', 'La extensión PHP-ZIP no está instalada en el servidor.');
        }

        // Carpeta temporal de extracción
        $tmpDir = storage_path('app/image-imports/'.uniqid('zip_', true));
        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        // Abrir ZIP
        $zip = new ZipArchive();
        $opened = $zip->open($request->file('file')->getRealPath());
        if ($opened !== true) {
            return back()->with('error', 'No se pudo abrir el ZIP (código: '.$opened.').');
        }

        $stats = [
            'attached' => 0,
            'skipped' => 0,
            'not_matched' => 0,
            'errors' => [],
        ];

        // Recolectar productos por internal_code (case-insensitive)
        $productByCode = Product::all()->keyBy(fn ($p) => Str::lower($p->internal_code));

        // Agrupar archivos por referencia para mantener orden -1, -2, -3
        $entries = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);
            $base = basename($entry);

            // Saltar carpetas, archivos ocultos y __MACOSX
            if (str_ends_with($entry, '/')) continue;
            if (str_starts_with($base, '.') || str_starts_with($base, '__')) continue;

            $info = pathinfo($base);
            $ext = strtolower($info['extension'] ?? '');
            if (! in_array($ext, self::VALID_EXT, true)) {
                $stats['skipped']++;
                continue;
            }

            // Detectar sufijo -N al final del nombre (ej "1307-1" → ref "1307", idx 1)
            $name = $info['filename'];
            $matchSuffix = preg_match('/^(.+)-(\d+)$/', $name, $m);
            if ($matchSuffix) {
                $ref = $m[1];
                $idx = (int) $m[2];
            } else {
                $ref = $name;
                $idx = 1;
            }

            $entries[] = [
                'zip_path' => $entry,
                'ref' => $ref,
                'idx' => $idx,
                'ext' => $ext,
            ];
        }

        // Ordenar por referencia + índice para procesar 1, 2, 3 en orden
        usort($entries, fn ($a, $b) => [$a['ref'], $a['idx']] <=> [$b['ref'], $b['idx']]);

        // Agrupar por referencia
        $byRef = [];
        foreach ($entries as $e) {
            $byRef[Str::lower($e['ref'])][] = $e;
        }

        // Procesar cada producto
        foreach ($byRef as $refLower => $items) {
            $product = $productByCode[$refLower] ?? null;

            if (! $product) {
                $stats['not_matched']++;
                $stats['errors'][] = "Referencia '{$items[0]['ref']}' no existe en el catálogo (".count($items)." archivos omitidos).";
                continue;
            }

            try {
                $storedPaths = $request->input('mode') === 'replace'
                    ? []
                    : ($product->images ?? []);

                $folder = 'products/'.$product->slug;
                Storage::disk('public')->makeDirectory($folder);

                foreach ($items as $i => $item) {
                    $rawBytes = $zip->getFromName($item['zip_path']);
                    if ($rawBytes === false) {
                        $stats['errors'][] = "[{$product->internal_code}] no se pudo extraer '{$item['zip_path']}'.";
                        continue;
                    }

                    $webp = $this->convertToOptimizedWebP($rawBytes, $item['ext']);
                    if ($webp === null) {
                        $stats['errors'][] = "[{$product->internal_code}] formato no procesable: '{$item['zip_path']}'.";
                        continue;
                    }

                    $filename = $item['idx'].'-'.uniqid().'.webp';
                    $relPath = $folder.'/'.$filename;
                    Storage::disk('public')->put($relPath, $webp);

                    $storedPaths[] = $relPath;
                    $stats['attached']++;
                }

                $product->images = array_values(array_unique($storedPaths));
                $product->save();
            } catch (\Throwable $e) {
                $stats['errors'][] = "[{$product->internal_code}] error: ".$e->getMessage();
                Log::warning('Image import failed for product', [
                    'product' => $product->internal_code,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $zip->close();
        $this->rrmdir($tmpDir);

        $msg = sprintf(
            'Imágenes procesadas: %d adjuntadas, %d sin match (referencia inexistente), %d omitidas (formato).',
            $stats['attached'],
            $stats['not_matched'],
            $stats['skipped'],
        );

        if (! empty($stats['errors'])) {
            session()->flash('import_image_errors', array_slice($stats['errors'], 0, 50));
        }

        return redirect()->route('admin.products.index')->with('success', $msg);
    }

    /**
     * Toma bytes de imagen, redimensiona y convierte a WebP.
     */
    private function convertToOptimizedWebP(string $bytes, string $ext): ?string
    {
        $img = @imagecreatefromstring($bytes);
        if (! $img) {
            return null;
        }

        $w = imagesx($img);
        $h = imagesy($img);

        // Redimensionar si excede el máximo
        if (max($w, $h) > self::MAX_DIM) {
            if ($w >= $h) {
                $newW = self::MAX_DIM;
                $newH = (int) round($h * self::MAX_DIM / $w);
            } else {
                $newH = self::MAX_DIM;
                $newW = (int) round($w * self::MAX_DIM / $h);
            }

            $resized = imagecreatetruecolor($newW, $newH);
            // Preservar alpha (PNG transparente)
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $newW, $newH, $transparent);

            imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($img);
            $img = $resized;
        } else {
            imagealphablending($img, false);
            imagesavealpha($img, true);
        }

        ob_start();
        imagewebp($img, null, self::WEBP_QUALITY);
        $webpBytes = ob_get_clean();
        imagedestroy($img);

        return $webpBytes !== false && strlen($webpBytes) > 0 ? $webpBytes : null;
    }

    private function rrmdir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir.DIRECTORY_SEPARATOR.$item;
            is_dir($path) ? $this->rrmdir($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}
