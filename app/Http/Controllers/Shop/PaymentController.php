<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\Options;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected Transaction $transaction;

    public function __construct(protected CartService $cartService)
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

    public function callback(Request $request)
    {
        $token = $request->input('token_ws');

        // Handling "Abort button" from Webpay form (sometimes returns TBK_TOKEN, TBK_ORDEN_COMPRA, TBK_ID_SESION)
        if (!$token) {
            $tbkToken = $request->input('TBK_TOKEN');
            if ($tbkToken) {
                // This means the user aborted the transaction
                return redirect()->route('checkout.index')->with('error', 'Pago abortado por el usuario.');
            }
            return redirect()->route('checkout.index')->with('error', 'Token de pago inválido.');
        }

        try {
            // Use the instance created in constructor
            $response = $this->transaction->commit($token);

            $order = Order::where('order_number', $response->getBuyOrder())->first();

            if (!$order) {
                return redirect()->route('checkout.index')->with('error', 'Orden no encontrada para esta transacción.');
            }

            if ($response->isApproved()) {
                $order->update([
                    'payment_status' => 'completed',
                    'current_status' => 'processing',
                ]);

                // Clear the cart on successful payment
                $user = Auth::user();
                $cart = $this->cartService->getCart($user);
                $this->cartService->clear($cart);

                return redirect()->route('checkout.success', $order);
            } else {
                $order->update(['payment_status' => 'failed']);
                return redirect()->route('checkout.index')->with('error', 'Pago rechazado por el banco.');
            }

        } catch (\Exception $e) {
            Log::error('Transbank Commit Error: ' . $e->getMessage());
            
            // Try to recover order from token if possible
            $order = Order::where('payment_transaction_id', $token)->first();
            if ($order) {
                $order->update(['payment_status' => 'failed']);
            }

            return redirect()->route('checkout.index')->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }
}
