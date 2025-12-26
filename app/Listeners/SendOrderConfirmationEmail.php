<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\OrderConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        // Email al Cliente
        Mail::to($event->order->customer_email)
            ->send(new OrderConfirmation($event->order));

        // Email al Admin
        $adminEmail = env('SHOP_EMAIL');
        if ($adminEmail) {
            Mail::to($adminEmail)
                ->send(new OrderConfirmation($event->order, true));
        }
    }
}
