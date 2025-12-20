<?php

namespace App\Services\Checkout;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Services\Cart\CartService;
use App\Services\Shipping\ShippingCalculator;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService,
        protected ShippingCalculator $shippingCalculator
    ) {}

    public function createOrder(?User $user, Cart $cart, array $data): Order
    {
        return DB::transaction(function () use ($user, $cart, $data) {
            
            // 1. Create or Get Addresses
            $shippingAddress = $this->createOrGetAddress($user, $data['shipping_address'], 'shipping');
            $billingAddress = isset($data['billing_address']) 
                ? $this->createOrGetAddress($user, $data['billing_address'], 'billing')
                : $shippingAddress;

            // 2. Calculate Totals
            $totals = $this->cartService->getCartTotals($cart);
            
            // 3. Calculate Shipping
            $shippingMethod = $data['shipping_method'] ?? 'standard';
            $shippingCost = $this->shippingCalculator->calculateCost($shippingMethod);

            // 4. Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => $user?->id, // Can be null
                'customer_email' => $user ? $user->email : $data['email'], // Use passed email for guest
                'customer_first_name' => $user ? $user->first_name : $data['shipping_address']['first_name'],
                'customer_last_name' => $user ? $user->last_name : $data['shipping_address']['last_name'],
                'customer_phone' => $user->phone ?? $data['shipping_address']['phone'] ?? null,
                'billing_address_id' => $billingAddress->id,
                'shipping_address_id' => $shippingAddress->id,
                'subtotal' => $totals['subtotal'],
                'tax_total' => $totals['tax'],
                'discount_total' => $totals['discount'],
                'shipping_cost' => $shippingCost,
                'grand_total' => $totals['total'] + $shippingCost,
                'payment_method' => $data['payment_method'],
                'shipping_method' => $shippingMethod,
                'current_status' => 'pending',
            ]);

            // 5. Create Order Items
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_combination_id' => $item->product_combination_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price_snapshot,
                    'tax_rate' => $item->product->tax->rate ?? 0,
                    'subtotal' => $item->price_snapshot * $item->quantity,
                    'total' => ($item->price_snapshot * $item->quantity), 
                ]);
            }

            // 6. Initial History
            $order->history()->create([
                'status' => 'pending',
                'comment' => 'Order created',
            ]);

            // 7. Clear Cart
            $this->cartService->clear($cart);

            // 8. Dispatch Event
            OrderCreated::dispatch($order);

            return $order;
        });
    }

    protected function createOrGetAddress(?User $user, array $data, string $type): Address
    {
        return Address::create(array_merge($data, [
            'user_id' => $user?->id,
            'address_type' => $type,
        ]));
    }
}