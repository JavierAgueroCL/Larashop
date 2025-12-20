<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function recordMovement(Product $product, int $quantity, string $type, ?string $reason = null, ?string $refType = null, ?int $refId = null): void
    {
        DB::transaction(function () use ($product, $quantity, $type, $reason, $refType, $refId) {
            
            // 1. Record Movement
            StockMovement::create([
                'product_id' => $product->id,
                'movement_type' => $type,
                'quantity' => $quantity,
                'reason' => $reason,
                'reference_type' => $refType,
                'reference_id' => $refId,
            ]);

            // 2. Update Product Stock
            if ($type === 'in' || $type === 'released') {
                $product->increment('stock_quantity', $quantity);
            } elseif ($type === 'out' || $type === 'reserved') {
                $product->decrement('stock_quantity', $quantity);
                
                if ($product->stock_quantity <= 0) {
                    \App\Events\ProductOutOfStock::dispatch($product);
                }
            }
        });
    }

    public function isAvailable(Product $product, int $quantity): bool
    {
        return $product->stock_quantity >= $quantity;
    }
}
