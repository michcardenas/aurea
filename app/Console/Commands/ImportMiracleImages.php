<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Migra las imágenes de productos desde el proyecto miracle (Laravel 9 en
 * producción) hacia este proyecto Belleza Áurea, matcheando por referencia.
 *
 * REQUIERE en storage/app/miracle-import/:
 *   - mapping.csv  (export SQL: referencia, ruta_imagen, orden, es_principal)
 *   - imagenes/    (carpeta con los archivos bajados desde
 *                   public/imagenes/productos/ del server miracle)
 *
 * El CSV debe tener estas 4 columnas (con o sin header):
 *   ref, path, orden, principal
 *
 * Cada imagen se:
 *   1. Redimensiona a max 1200px (lado largo)
 *   2. Convierte a WebP q85
 *   3. Guarda en storage/app/public/products/{slug}/{idx}-{rand}.webp
 *   4. Se añade al campo images[] del producto (principal primero)
 *
 * Uso:
 *   php artisan products:import-miracle-images               # corre real
 *   php artisan products:import-miracle-images --dry-run     # solo simula
 *   php artisan products:import-miracle-images --replace     # reemplaza existentes
 */
class ImportMiracleImages extends Command
{
    protected $signature = 'products:import-miracle-images
                            {--dry-run : No escribe archivos ni DB, solo reporta}
                            {--replace : Reemplaza imágenes existentes del producto}
                            {--csv= : Ruta personalizada del CSV}
                            {--imgs= : Carpeta personalizada de imágenes}';

    protected $description = 'Migra imágenes desde miracle (matching por referencia) optimizadas a WebP';

    private const MAX_DIM = 1200;
    private const WEBP_QUALITY = 85;

    public function handle(): int
    {
        @ini_set('memory_limit', '2048M');
        set_time_limit(0);

        $base = storage_path('app/miracle-import');
        $csvPath  = $this->option('csv')  ?: $base.'/mapping.csv';
        $imgsRoot = $this->option('imgs') ?: $base.'/imagenes';
        $dryRun   = $this->option('dry-run');
        $replace  = $this->option('replace');

        if (! file_exists($csvPath)) {
            $this->error('No encuentro el CSV: '.$csvPath);
            $this->line('Pon el archivo en: '.$base.'/mapping.csv');
            return self::FAILURE;
        }
        if (! is_dir($imgsRoot)) {
            $this->error('No encuentro la carpeta de imágenes: '.$imgsRoot);
            return self::FAILURE;
        }

        $this->info('==> Migración miracle → Belleza Áurea');
        $this->line('    CSV:    '.$csvPath);
        $this->line('    IMGS:   '.$imgsRoot);
        $this->line('    Modo:   '.($dryRun ? 'DRY RUN (no escribe)' : 'REAL'));
        $this->line('    Replace:'.($replace ? 'SÍ borra existentes' : 'NO, añade'));
        $this->newLine();

        $rows = $this->readCsv($csvPath);
        $this->info('Filas CSV: '.count($rows));

        $byRef = collect($rows)
            ->groupBy(fn ($r) => Str::lower(trim((string) $r['ref'])))
            ->map(fn ($g) => $g->sortBy([
                ['principal', 'desc'],
                ['orden', 'asc'],
            ])->values());

        $this->info('Referencias únicas: '.$byRef->count());

        $productByCode = Product::all()->keyBy(fn ($p) => Str::lower($p->internal_code));
        $this->info('Productos en aurea: '.$productByCode->count());

        $availableFiles = $this->scanFiles($imgsRoot);
        $this->info('Archivos en imagenes/: '.count($availableFiles));
        $this->newLine();

        $stats = [
            'products_matched'   => 0,
            'products_no_match'  => 0,
            'images_attached'    => 0,
            'images_not_found'   => 0,
            'images_skipped'     => 0,
            'errors'             => [],
        ];

        $bar = $this->output->createProgressBar($byRef->count());
        $bar->start();

        foreach ($byRef as $refLower => $items) {
            $bar->advance();

            $product = $productByCode[$refLower] ?? null;
            if (! $product) {
                $stats['products_no_match']++;
                $stats['errors'][] = "[no-match] ref='$refLower' no existe en aurea ({$items->count()} imágenes ignoradas)";
                continue;
            }

            $stats['products_matched']++;
            $storedPaths = $replace ? [] : ($product->images ?? []);
            $folder = 'products/'.$product->slug;

            if (! $dryRun) {
                Storage::disk('public')->makeDirectory($folder);
            }

            foreach ($items as $idx => $row) {
                $sourcePath = $this->findFile($row['path'], $imgsRoot, $availableFiles);
                if (! $sourcePath) {
                    $stats['images_not_found']++;
                    $stats['errors'][] = "[{$product->internal_code}] no encuentro '{$row['path']}'";
                    continue;
                }

                if ($dryRun) {
                    $stats['images_attached']++;
                    continue;
                }

                try {
                    $bytes = file_get_contents($sourcePath);
                    $webp = $this->optimizeToWebP($bytes);
                    if ($webp === null) {
                        $stats['images_skipped']++;
                        $stats['errors'][] = "[{$product->internal_code}] no procesable: ".basename($sourcePath);
                        continue;
                    }

                    $filename = ($idx + 1).'-'.uniqid().'.webp';
                    $relPath = $folder.'/'.$filename;
                    Storage::disk('public')->put($relPath, $webp);

                    $storedPaths[] = $relPath;
                    $stats['images_attached']++;
                } catch (\Throwable $e) {
                    $stats['images_skipped']++;
                    $stats['errors'][] = "[{$product->internal_code}] error: ".$e->getMessage();
                }
            }

            if (! $dryRun) {
                $product->images = array_values(array_unique($storedPaths));
                $product->save();
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('RESUMEN');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line('  Productos matcheados:   '.$stats['products_matched']);
        $this->line('  Productos sin match:    '.$stats['products_no_match']);
        $this->line('  Imágenes adjuntadas:    '.$stats['images_attached']);
        $this->line('  Imágenes no encontradas:'.$stats['images_not_found']);
        $this->line('  Imágenes con error:     '.$stats['images_skipped']);

        if (! empty($stats['errors'])) {
            $this->newLine();
            $this->warn('Errores (primeros 30):');
            foreach (array_slice($stats['errors'], 0, 30) as $err) {
                $this->line('  • '.$err);
            }
            if (count($stats['errors']) > 30) {
                $this->line('  ... y '.(count($stats['errors']) - 30).' más.');
            }
        }

        if ($dryRun) {
            $this->newLine();
            $this->warn('DRY RUN — Nada se guardó. Quita --dry-run para correr de verdad.');
        }

        return self::SUCCESS;
    }

    private function readCsv(string $path): array
    {
        $rows = [];
        if (($h = fopen($path, 'r')) === false) return $rows;

        $headerSeen = false;
        while (($cols = fgetcsv($h, 0, ',')) !== false) {
            if (count($cols) < 2) continue;
            if (! $headerSeen) {
                $headerSeen = true;
                $firstLower = strtolower(trim((string) $cols[0]));
                if (in_array($firstLower, ['ref', 'referencia', 'sku', 'codigo'], true)) {
                    continue;
                }
            }
            $rows[] = [
                'ref'      => trim((string) ($cols[0] ?? '')),
                'path'     => trim((string) ($cols[1] ?? '')),
                'orden'    => (int) ($cols[2] ?? 0),
                'principal'=> (int) ($cols[3] ?? 0),
            ];
        }
        fclose($h);
        return $rows;
    }

    /**
     * Escanea recursivo la carpeta y devuelve map basename → full path.
     */
    private function scanFiles(string $root): array
    {
        $out = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if ($file->isFile()) {
                $out[basename($file->getPathname())] = $file->getPathname();
            }
        }
        return $out;
    }

    /**
     * Encuentra el archivo intentando varias estrategias por si el path
     * del CSV viene con prefijo (imagenes/productos/...) o sin él.
     */
    private function findFile(string $relPath, string $root, array $available): ?string
    {
        $relPath = ltrim($relPath, '/\\');
        $candidates = [
            $root.'/'.$relPath,
            $root.'/'.preg_replace('#^imagenes/#', '', $relPath),
            $root.'/'.preg_replace('#^imagenes/productos/#', '', $relPath),
        ];
        foreach ($candidates as $c) {
            if (is_file($c)) return $c;
        }
        // Fallback por basename — útil si el usuario aplanó la carpeta
        $base = basename($relPath);
        return $available[$base] ?? null;
    }

    private function optimizeToWebP(string $bytes): ?string
    {
        $img = @imagecreatefromstring($bytes);
        if (! $img) return null;

        $w = imagesx($img); $h = imagesy($img);
        if (max($w, $h) > self::MAX_DIM) {
            if ($w >= $h) {
                $newW = self::MAX_DIM;
                $newH = (int) round($h * self::MAX_DIM / $w);
            } else {
                $newH = self::MAX_DIM;
                $newW = (int) round($w * self::MAX_DIM / $h);
            }
            $resized = imagecreatetruecolor($newW, $newH);
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
        $out = ob_get_clean();
        imagedestroy($img);
        return $out !== false && strlen($out) > 0 ? $out : null;
    }
}
