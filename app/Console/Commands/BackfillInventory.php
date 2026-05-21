<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Comando one-shot: descuenta el stock historico de las ordenes ya cerradas
 * (paid o status confirmed/shipped/delivered) que aun no fueron procesadas.
 *
 * Uso:
 *   php artisan inventory:backfill              -> ejecuta el descuento
 *   php artisan inventory:backfill --dry-run    -> muestra que haria sin tocar la BD
 *
 * NOTA: no es idempotente. Correrlo dos veces descontara dos veces. Hazlo una
 * sola vez, revisa el resumen y listo.
 */
class BackfillInventory extends Command
{
    protected $signature = 'inventory:backfill {--dry-run : Solo muestra el resumen sin tocar el inventario}';

    protected $description = 'Descuenta el inventario historicamente para las ordenes ya cerradas (paid/confirmed/shipped/delivered).';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $this->info($dryRun ? '── DRY RUN: no se modificara el inventario ──' : '── Procesando descuento de inventario ──');

        $orders = Order::where(function ($q) {
                $q->where('payment_status', 'paid')
                  ->orWhereIn('status', ['confirmed', 'shipped', 'delivered']);
            })
            ->with('items')
            ->orderBy('id')
            ->get();

        $this->line('Órdenes elegibles: ' . $orders->count());

        $totalItems = 0;
        $totalUnits = 0;
        $touched = ['variants' => [], 'products' => []];

        $callback = function () use ($orders, $dryRun, &$totalItems, &$totalUnits, &$touched) {
            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    $qty = (int) $item->qty;
                    if ($qty <= 0) {
                        continue;
                    }
                    $totalItems++;
                    $totalUnits += $qty;

                    if ($item->variant_id) {
                        $touched['variants'][$item->variant_id] = ($touched['variants'][$item->variant_id] ?? 0) + $qty;
                        if (! $dryRun) {
                            DB::table('product_variants')
                                ->where('id', $item->variant_id)
                                ->update(['stock' => DB::raw('GREATEST(stock - ' . $qty . ', 0)')]);
                        }
                    } elseif ($item->product_id) {
                        $touched['products'][$item->product_id] = ($touched['products'][$item->product_id] ?? 0) + $qty;
                        if (! $dryRun) {
                            DB::table('products')
                                ->where('id', $item->product_id)
                                ->update(['stock' => DB::raw('GREATEST(stock - ' . $qty . ', 0)')]);
                        }
                    }
                }
            }
        };

        if ($dryRun) {
            $callback();
        } else {
            DB::transaction($callback);
        }

        $this->newLine();
        $this->info("Order items procesados: {$totalItems}");
        $this->info("Unidades totales descontadas: {$totalUnits}");
        $this->line('Variantes afectadas: ' . count($touched['variants']));
        $this->line('Productos (sin variante) afectados: ' . count($touched['products']));

        if ($dryRun) {
            $this->newLine();
            $this->warn('DRY RUN: no se aplicaron cambios. Vuelve a correr sin --dry-run para ejecutarlos.');
        } else {
            $this->newLine();
            $this->info('✅ Descuento aplicado.');
        }

        return self::SUCCESS;
    }
}
