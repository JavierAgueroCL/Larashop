<?php

namespace App\Services\Payment;

use App\Models\Order;

class BankTransferPaymentService implements PaymentServiceInterface
{
    public function process(Order $order, array $data): bool
    {
        // For bank transfer, we just mark it as pending payment and provide instructions.
        // In a real app, this might just return true, and the user sees instructions on success page.
        
        $order->update([
            'payment_status' => 'pending',
            'notes' => $order->notes . "\nWaiting for bank transfer.",
        ]);

        return true;
    }

    public function getRedirectUrl(Order $order): ?string
    {
        return null; // No external redirect needed
    }
}
