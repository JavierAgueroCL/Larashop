<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\Options;

class TransbankPaymentService implements PaymentServiceInterface
{
    protected ?string $redirectUrl = null;
    protected Transaction $transaction;

    public function __construct()
    {
        $environment = config('services.transbank.environment', 'integration');
        
        if ($environment === 'production') {
            $this->transaction = new Transaction(new Options(
                config('services.transbank.api_key'),
                config('services.transbank.commerce_code'),
                Options::ENVIRONMENT_PRODUCTION
            ));
        } else {
            $this->transaction = new Transaction(new Options(
                WebpayPlus::INTEGRATION_API_KEY,
                WebpayPlus::INTEGRATION_COMMERCE_CODE,
                Options::ENVIRONMENT_INTEGRATION
            ));
        }
    }

    public function process(Order $order, array $data): bool
    {
        try {
            $buyOrder = $order->order_number; // Using order_number which is unique string
            $sessionId = session()->getId();
            $amount = (int) $order->grand_total; // Webpay standard is Integer (CLP)

            $returnUrl = route('payment.transbank.callback');

            // Use the instance created in constructor
            $response = $this->transaction->create($buyOrder, $sessionId, $amount, $returnUrl);

            // Webpay Plus Redirect: URL + token_ws (GET)
            $this->redirectUrl = $response->getUrl() . '?token_ws=' . $response->getToken();
            
            // Store token to verify correlation later
            $order->update(['payment_transaction_id' => $response->getToken()]);

            return true;

        } catch (\Exception $e) {
            Log::error('Transbank Payment Error: ' . $e->getMessage());
            return false;
        }
    }

    public function getRedirectUrl(Order $order): ?string
    {
        return $this->redirectUrl;
    }
}