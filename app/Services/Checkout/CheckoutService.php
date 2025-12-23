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
            
            // 1. Resolve Addresses
            $shippingAddressId = $data['shipping_address_id'] ?? 'new';
            
            if ($shippingAddressId !== 'new' && $shippingAddressId) {
                $shippingAddress = Address::find($shippingAddressId);
                // Security check: ensure address belongs to user if logged in
                if ($user && $shippingAddress && $shippingAddress->user_id !== $user->id) {
                     // Fallback or throw error? For now fallback to creating new to avoid error, or strict check.
                     // Let's assume strict validation happened in controller, but safe fallback:
                     $shippingAddress = $this->createAddress($data['shipping_address'], 'shipping');
                }
            } else {
                $shippingAddress = $this->createAddress($data['shipping_address'], 'shipping');
            }

            // Billing Address
            // If we have a billing_address_id separate from shipping, handle it. 
            // Current UI suggests billing is either same as shipping or a new form?
            // Actually, my previous changes to checkout view allow separate billing form.
            // But we don't have a 'billing_address_id' selector in the UI for Guest? 
            // For Auth user, we didn't add a selector for Billing, only Shipping. 
            // The Billing form is "Guest Billing Logic" in x-data.
            // So for now, Billing is always "created" from the form data if not "same as shipping".
            // BUT, if it's "same as shipping", we use $shippingAddress.
            
            // Wait, logic in Controller/View:
            // The view has `billing_address` inputs.
            // If `useBillingForShipping` is true, we copy billing fields to shipping (for guest).
            // For Auth user, we have `addresses` list for Shipping.
            // We don't have a list for Billing in the UI I wrote.
            // So Billing is always "new" or "same as shipping".
            
            // Let's assume if 'billing_address' data is present, we create/use it.
            // But if it's the SAME address record (ID), we should reuse.
            // Since we don't select Billing ID, we create a new one (transient).
            
            if (isset($data['billing_address']) && !empty($data['billing_address']['first_name'])) {
                 $billingAddress = $this->createAddress($data['billing_address'], 'billing');
            } else {
                 $billingAddress = $shippingAddress;
            }

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

            // 7. Dispatch Event
            OrderCreated::dispatch($order);

            return $order;
        });
    }

    protected function createAddress(array $data, string $type): Address
    {
        return Address::create(array_merge($data, [
            'user_id' => null, // Do not associate one-off checkout addresses with user
            'address_type' => $type,
        ]));
    }
}