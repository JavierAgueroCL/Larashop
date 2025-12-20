<?php

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusChanged as OrderStatusChangedMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('order confirmation email is sent when order is created', function () {
    Mail::fake();

    $user = User::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'customer_email' => $user->email,
    ]);

    event(new OrderCreated($order));

    Mail::assertSent(OrderConfirmation::class, function ($mail) use ($order) {
        return $mail->order->id === $order->id &&
               $mail->hasTo($order->customer_email);
    });
});

test('order status changed email is sent when status is updated', function () {
    Mail::fake();

    $order = Order::factory()->create();

    event(new OrderStatusChanged($order));

    Mail::assertSent(OrderStatusChangedMail::class, function ($mail) use ($order) {
        return $mail->order->id === $order->id &&
               $mail->hasTo($order->customer_email);
    });
});


    