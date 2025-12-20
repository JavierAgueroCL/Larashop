<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentServiceInterface
{
    public function process(Order $order, array $data): bool;
    public function getRedirectUrl(Order $order): ?string;
}
