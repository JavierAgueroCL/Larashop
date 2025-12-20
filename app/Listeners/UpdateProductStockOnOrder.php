<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\Product\StockService;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductStockOnOrder
{
    public function __construct(protected StockService $stockService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        foreach ($event->order->items as $item) {
            if ($item->product) {
                $this->stockService->recordMovement(
                    $item->product,
                    $item->quantity,
                    'out',
                    'Order #' . $event->order->order_number,
                    'Order',
                    $event->order->id
                );
            }
        }
    }
}
