<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutService;
use App\Services\Payment\PaymentManager;
use App\Services\Shipping\ShippingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
        protected PaymentManager $paymentManager,
        protected ShippingCalculator $shippingCalculator
    ) {}

    public function index(): View|RedirectResponse
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
        
        // Dummy address for calculator if no address selected yet (simplified for MVP)
        // In a real scenario, we'd AJAX update this when address changes.
        // For now, we assume "Europe" zone default.
        $dummyAddress = new \App\Models\Address(['country_code' => 'ES']); 
        $carriers = $this->shippingCalculator->getAvailableCarriers($dummyAddress, $cart);
        $regions = \App\Models\Region::all();

        return view('shop.checkout.index', compact('cart', 'totals', 'user', 'addresses', 'carriers', 'regions'));
    }

    public function process(Request $request)
    {
        // Validation
        $rules = [
            'payment_method' => 'required',
        ];

        // Only validate shipping address fields if creating a new one
        if ($request->input('shipping_address_id') === 'new') {
            $rules = array_merge($rules, [
                'shipping_address.alias' => 'nullable|string|max:255',
                'shipping_address.first_name' => 'required',
                'shipping_address.last_name' => 'required',
                'shipping_address.address_line_1' => 'required',
                'shipping_address.region_id' => 'required|exists:regiones,id',
                'shipping_address.comuna_id' => 'required|exists:comunas,id',
                'shipping_address.country_code' => 'required',
                'shipping_address.phone' => 'required',
            ]);
        }

        if (!Auth::check()) {
            $rules['email'] = 'required|email';
            $rules['create_account'] = 'nullable|boolean';
            $rules['password'] = 'required_if:create_account,1|confirmed';
            
            $rules['billing_address.first_name'] = 'required';
            $rules['billing_address.last_name'] = 'required';
            $rules['billing_address.address_line_1'] = 'required';
            $rules['billing_address.region_id'] = 'required|exists:regiones,id';
            $rules['billing_address.comuna_id'] = 'required|exists:comunas,id';
            $rules['billing_address.country_code'] = 'required';
            $rules['billing_address.phone'] = 'required';
            
            $rules['billing_address.document_type'] = 'required|in:boleta,factura';
            $rules['billing_address.rut'] = 'required|string|max:20';
            $rules['billing_address.company'] = 'required_if:billing_address.document_type,factura|nullable|string|max:255';
            $rules['billing_address.business_activity'] = 'required_if:billing_address.document_type,factura|nullable|string|max:255';
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
            $data = $request->all();
            if (isset($data['billing_address']['document_type'])) {
                $data['document_type'] = $data['billing_address']['document_type'];
            }
            $order = $this->checkoutService->createOrder($user, $cart, $data);
            
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
            
            // For manual methods (bank transfer), clear cart now
            $this->cartService->clear($cart);
            
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