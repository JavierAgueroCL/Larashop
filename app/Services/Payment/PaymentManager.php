<?php

namespace App\Services\Payment;

use Illuminate\Support\Manager;

class PaymentManager extends Manager
{
    public function getDefaultDriver()
    {
        return config('payment.default', 'bank_transfer');
    }

    public function createBankTransferDriver()
    {
        return new BankTransferPaymentService();
    }

    public function createPaypalDriver()
    {
        return new PayPalPaymentService();
    }
}
