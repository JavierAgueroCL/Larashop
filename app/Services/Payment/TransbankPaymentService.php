<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;

class TransbankPaymentService implements PaymentServiceInterface
{
    protected ?string $redirectUrl = null;

    public function __construct()
    {
        $environment = config('services.transbank.environment', 'integration');
        
        if ($environment === 'production') {
            WebpayPlus::configureForProduction(
                config('services.transbank.commerce_code'),
                config('services.transbank.api_key')
            );
        } else {
            WebpayPlus::configureForIntegration(
                WebpayPlus::DEFAULT_COMMERCE_CODE,
                WebpayPlus::DEFAULT_API_KEY
            );
        }
    }

    public function process(Order $order, array $data): bool
    {
        try {
            $transaction = new Transaction();
            $buyOrder = $order->order_number; // Using order_number which is unique string
            $sessionId = session()->getId();
            $amount = (int) $order->grand_total; // Webpay standard is Integer (CLP)

            $returnUrl = route('payment.transbank.callback');

            $response = $transaction->create($buyOrder, $sessionId, $amount, $returnUrl);

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
