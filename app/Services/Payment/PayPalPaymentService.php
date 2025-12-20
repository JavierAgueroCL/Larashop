<?php

namespace App\Services\Payment;

use App\Models\Order;

class PayPalPaymentService implements PaymentServiceInterface
{
    public function process(Order $order, array $data): bool
    {
        // Mock implementation
        // 1. Call PayPal API to create payment intent
        // 2. Return true if successful
        
        return true; 
    }

    public function getRedirectUrl(Order $order): ?string
    {
        // Return mock PayPal URL
        return "https://www.sandbox.paypal.com/checkoutnow?token=mock_token_" . $order->id;
    }
}
