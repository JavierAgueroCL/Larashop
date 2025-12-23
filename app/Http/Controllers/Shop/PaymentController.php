<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;

class PaymentController extends Controller
{
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

    public function callback(Request $request)
    {
        $token = $request->input('token_ws');

        // Handling "Abort button" from Webpay form (sometimes returns TBK_TOKEN, TBK_ORDEN_COMPRA, TBK_ID_SESION)
        if (!$token) {
            $tbkToken = $request->input('TBK_TOKEN');
            if ($tbkToken) {
                // This means the user aborted the transaction
                // We should find the order and cancel it or just show failed message
                // Usually we can't commit an aborted transaction.
                return redirect()->route('checkout.index')->with('error', 'Payment aborted by user.');
            }
            return redirect()->route('checkout.index')->with('error', 'Invalid payment token.');
        }

        try {
            $transaction = new Transaction();
            $response = $transaction->commit($token);

            $order = Order::where('order_number', $response->getBuyOrder())->first();

            if (!$order) {
                return redirect()->route('checkout.index')->with('error', 'Order not found for this transaction.');
            }

            if ($response->isApproved()) {
                $order->update([
                    'payment_status' => 'completed',
                    'current_status' => 'processing',
                    // Save card details if needed? $response->getCardDetail()
                ]);

                return redirect()->route('checkout.success', $order);
            } else {
                $order->update(['payment_status' => 'failed']);
                return redirect()->route('checkout.index')->with('error', 'Payment rejected by bank.');
            }

        } catch (\Exception $e) {
            Log::error('Transbank Commit Error: ' . $e->getMessage());
            
            // Try to recover order from token if possible, but commit failed so we assume failure
            // We can't easily find order without buyOrder which comes from commit response usually, 
            // unless we query by payment_transaction_id.
            $order = Order::where('payment_transaction_id', $token)->first();
            if ($order) {
                $order->update(['payment_status' => 'failed']);
            }

            return redirect()->route('checkout.index')->with('error', 'Payment processing error: ' . $e->getMessage());
        }
    }
}
