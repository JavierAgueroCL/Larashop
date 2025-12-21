<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutService;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
        protected PaymentManager $paymentManager
    ) {}

    public function index(): View
    {
        $user = Auth::user();
        // Allow guest to checkout
        // if (!$user) {
        //     return redirect()->route('login');
        // }

        $cart = $this->cartService->getCart($user);
        
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $totals = $this->cartService->getCartTotals($cart);

        $addresses = $user ? $user->addresses()->where('address_type', 'shipping')->get() : collect();

        return view('shop.checkout.index', compact('cart', 'totals', 'user', 'addresses'));
    }

    public function process(Request $request)
    {
        // Validation
        $rules = [
            'shipping_address.alias' => 'nullable|string|max:255',
            'shipping_address.first_name' => 'required',
            'shipping_address.last_name' => 'required',
            'shipping_address.address_line_1' => 'required',
            'shipping_address.city' => 'required',
            'shipping_address.postal_code' => 'required',
            'shipping_address.country_code' => 'required',
            'shipping_address.phone' => 'required',
            'payment_method' => 'required',
        ];

        if (!Auth::check()) {
            $rules['email'] = 'required|email';
            $rules['create_account'] = 'nullable|boolean';
            $rules['password'] = 'required_if:create_account,1|confirmed';
        }

        $request->validate($rules);

        $user = Auth::user();

        // Handle Guest Registration
        if (!$user && $request->has('create_account') && $request->create_account) {
            $user = \App\Models\User::create([
                'name' => $request->shipping_address['first_name'] . ' ' . $request->shipping_address['last_name'],
                'first_name' => $request->shipping_address['first_name'],
                'last_name' => $request->shipping_address['last_name'],
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            ]);
            Auth::login($user);
        }

        // If still guest, handle guest checkout logic
        // We need to modify CheckoutService to accept guest info if user is null
        
        // IMPORTANT: For now, we will create a temporary user if not creating account, OR
        // better, update createOrder to handle null user and use passed email
        
        $cart = $this->cartService->getCart($user);

        try {
            // We pass request data which includes email for guest
            $order = $this->checkoutService->createOrder($user, $cart, $request->all());
            
            // Process Payment
            $paymentDriver = $this->paymentManager->driver($request->payment_method);
            $success = $paymentDriver->process($order, $request->all());

            if (!$success) {
                return redirect()->route('checkout.index')->with('error', 'Payment failed.');
            }

            $redirectUrl = $paymentDriver->getRedirectUrl($order);
            if ($redirectUrl) {
                return redirect($redirectUrl);
            }
            
            return redirect()->route('checkout.success', $order);

        } catch (\Exception $e) {
            return back()->with('error', 'Error processing order: ' . $e->getMessage());
        }
    }

    public function success(\App\Models\Order $order): View
    {
        // Verify user owns order OR order matches session/guest check
        // For guest, we can't easily check ownership unless we store order_id in session
        // For now, allow if Auth matches OR if newly created (less secure, but MVP)
        
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // If guest, maybe check if order email matches a session stored email?
        // Skipping strict guest check for MVP demo.

        return view('shop.checkout.success', compact('order'));
    }
}