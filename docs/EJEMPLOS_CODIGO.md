# Ejemplos de Código - LaraShop

Este documento contiene ejemplos de implementación para los principales componentes del sistema.

---

## ÍNDICE

1. [Servicios](#servicios)
2. [Acciones](#acciones)
3. [DTOs](#dtos)
4. [Eventos y Listeners](#eventos-y-listeners)
5. [Middleware](#middleware)
6. [Form Requests](#form-requests)
7. [Componentes Blade](#componentes-blade)
8. [Controllers](#controllers)

---

## SERVICIOS

### PriceCalculator Service

```php
<?php

namespace App\Services\Pricing;

use App\Models\Product;
use App\Models\User;
use App\Models\PriceRule;

class PriceCalculator
{
    public function __construct(
        protected TaxCalculator $taxCalculator
    ) {}

    /**
     * Calcula el precio final de un producto para un usuario específico
     */
    public function calculate(Product $product, ?User $user = null, int $quantity = 1): array
    {
        $basePrice = $product->base_price;

        // Aplicar price rules
        $discountedPrice = $this->applyPriceRules($product, $basePrice, $user, $quantity);

        // Calcular impuestos
        $taxAmount = $this->taxCalculator->calculate($discountedPrice, $product->tax);

        return [
            'base_price' => $basePrice,
            'discounted_price' => $discountedPrice,
            'discount_amount' => $basePrice - $discountedPrice,
            'tax_rate' => $product->tax?->rate ?? 0,
            'tax_amount' => $taxAmount,
            'final_price' => $discountedPrice + $taxAmount,
            'final_price_without_tax' => $discountedPrice,
        ];
    }

    /**
     * Aplica reglas de precio
     */
    protected function applyPriceRules(Product $product, float $basePrice, ?User $user, int $quantity): float
    {
        $rules = PriceRule::where('is_active', true)
            ->where(function ($query) use ($product, $user, $quantity) {
                // Reglas por producto
                $query->where('product_id', $product->id)
                    ->orWhereHas('category', function ($q) use ($product) {
                        $q->whereIn('id', $product->categories->pluck('id'));
                    });

                // Reglas por grupo de cliente
                if ($user && $user->customer_group_id) {
                    $query->orWhere('customer_group_id', $user->customer_group_id);
                }

                // Reglas por cantidad
                $query->orWhere(function ($q) use ($quantity) {
                    $q->where('rule_type', 'quantity')
                      ->where('min_quantity', '<=', $quantity);
                });
            })
            ->orderBy('priority', 'desc')
            ->get();

        $discountedPrice = $basePrice;

        foreach ($rules as $rule) {
            if ($rule->discount_type === 'percentage') {
                $discountedPrice -= ($discountedPrice * $rule->discount_value / 100);
            } else {
                $discountedPrice -= $rule->discount_value;
            }
        }

        return max($discountedPrice, 0);
    }
}
```

### TaxCalculator Service

```php
<?php

namespace App\Services\Pricing;

use App\Models\Tax;

class TaxCalculator
{
    /**
     * Calcula el monto de impuesto para un precio dado
     */
    public function calculate(float $price, ?Tax $tax = null): float
    {
        if (!$tax) {
            return 0;
        }

        return round($price * ($tax->rate / 100), 2);
    }

    /**
     * Calcula el precio con impuestos incluidos
     */
    public function priceWithTax(float $price, Tax $tax): float
    {
        return $price + $this->calculate($price, $tax);
    }

    /**
     * Calcula el precio sin impuestos (extrae el impuesto del precio)
     */
    public function priceWithoutTax(float $priceWithTax, Tax $tax): float
    {
        return round($priceWithTax / (1 + ($tax->rate / 100)), 2);
    }

    /**
     * Obtiene el impuesto aplicable según país y región
     */
    public function getTaxForLocation(string $countryCode, ?string $stateProvince = null): ?Tax
    {
        return Tax::where('is_active', true)
            ->where('country_code', $countryCode)
            ->where(function ($query) use ($stateProvince) {
                $query->whereNull('state_province')
                      ->orWhere('state_province', $stateProvince);
            })
            ->first();
    }
}
```

### CartService

```php
<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductCombination;
use App\Models\User;
use App\Exceptions\InsufficientStockException;
use App\Services\Product\StockService;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function __construct(
        protected StockService $stockService,
        protected CartCalculator $cartCalculator
    ) {}

    /**
     * Obtiene o crea un carrito para el usuario o sesión
     */
    public function getCart(?User $user = null): Cart
    {
        if ($user) {
            return Cart::firstOrCreate(['user_id' => $user->id]);
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Añade un producto al carrito
     */
    public function addItem(
        Cart $cart,
        Product $product,
        int $quantity = 1,
        ?ProductCombination $combination = null
    ): void {
        // Verificar stock
        if (!$this->stockService->isAvailable($product, $quantity, $combination)) {
            throw new InsufficientStockException(
                "No hay suficiente stock disponible para {$product->name}"
            );
        }

        // Buscar si ya existe el item
        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_combination_id', $combination?->id)
            ->first();

        if ($cartItem) {
            // Verificar stock para la nueva cantidad
            $newQuantity = $cartItem->quantity + $quantity;
            if (!$this->stockService->isAvailable($product, $newQuantity, $combination)) {
                throw new InsufficientStockException(
                    "No hay suficiente stock para añadir {$quantity} unidades más"
                );
            }

            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_combination_id' => $combination?->id,
                'quantity' => $quantity,
                'price_snapshot' => $combination ?
                    ($product->base_price + $combination->price_impact) :
                    $product->base_price,
            ]);
        }

        $cart->touch();
    }

    /**
     * Elimina un producto del carrito
     */
    public function removeItem(Cart $cart, int $cartItemId): void
    {
        $cart->items()->where('id', $cartItemId)->delete();
        $cart->touch();
    }

    /**
     * Actualiza la cantidad de un item
     */
    public function updateQuantity(Cart $cart, int $cartItemId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($cart, $cartItemId);
            return;
        }

        $cartItem = $cart->items()->findOrFail($cartItemId);

        // Verificar stock
        if (!$this->stockService->isAvailable($cartItem->product, $quantity, $cartItem->combination)) {
            throw new InsufficientStockException(
                "No hay suficiente stock disponible"
            );
        }

        $cartItem->update(['quantity' => $quantity]);
        $cart->touch();
    }

    /**
     * Vacía el carrito
     */
    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update(['coupon_id' => null]);
    }

    /**
     * Aplica un cupón al carrito
     */
    public function applyCoupon(Cart $cart, string $couponCode): void
    {
        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->firstOrFail();

        // Validar usos
        if ($coupon->max_uses && $coupon->uses_count >= $coupon->max_uses) {
            throw new \Exception('Este cupón ya no está disponible');
        }

        // Validar compra mínima
        $subtotal = $this->cartCalculator->calculateSubtotal($cart);
        if ($coupon->min_purchase_amount && $subtotal < $coupon->min_purchase_amount) {
            throw new \Exception(
                "Compra mínima de {$coupon->min_purchase_amount}€ requerida para este cupón"
            );
        }

        $cart->update(['coupon_id' => $coupon->id]);
    }

    /**
     * Transfiere items de un carrito de invitado a un carrito de usuario
     */
    public function mergeGuestCart(Cart $guestCart, Cart $userCart): void
    {
        foreach ($guestCart->items as $item) {
            $existingItem = $userCart->items()
                ->where('product_id', $item->product_id)
                ->where('product_combination_id', $item->product_combination_id)
                ->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $item->quantity);
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }
}
```

### OrderService

```php
<?php

namespace App\Services\Order;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use App\Events\OrderCreated;
use App\Services\Product\StockService;
use App\Services\Cart\CartCalculator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected StockService $stockService,
        protected CartCalculator $cartCalculator
    ) {}

    /**
     * Crea una orden desde un carrito
     */
    public function createFromCart(
        Cart $cart,
        Address $billingAddress,
        Address $shippingAddress,
        string $paymentMethod,
        string $shippingMethod,
        array $additionalData = []
    ): Order {
        return DB::transaction(function () use (
            $cart,
            $billingAddress,
            $shippingAddress,
            $paymentMethod,
            $shippingMethod,
            $additionalData
        ) {
            // Calcular totales
            $totals = $this->cartCalculator->calculateTotals($cart);

            // Crear orden
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $cart->user_id,
                'customer_email' => $additionalData['email'] ?? $cart->user->email,
                'customer_first_name' => $billingAddress->first_name,
                'customer_last_name' => $billingAddress->last_name,
                'customer_phone' => $billingAddress->phone,
                'billing_address_id' => $billingAddress->id,
                'shipping_address_id' => $shippingAddress->id,
                'subtotal' => $totals['subtotal'],
                'tax_total' => $totals['tax_total'],
                'shipping_cost' => $totals['shipping_cost'],
                'discount_total' => $totals['discount_total'],
                'grand_total' => $totals['grand_total'],
                'coupon_id' => $cart->coupon_id,
                'coupon_discount' => $totals['coupon_discount'] ?? 0,
                'payment_method' => $paymentMethod,
                'shipping_method' => $shippingMethod,
                'current_status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Crear items de la orden
            foreach ($cart->items as $cartItem) {
                $product = $cartItem->product;
                $combination = $cartItem->combination;

                $unitPrice = $combination
                    ? ($product->base_price + $combination->price_impact)
                    : $product->base_price;

                $taxRate = $product->tax?->rate ?? 0;
                $taxAmount = round($unitPrice * ($taxRate / 100), 2);
                $subtotal = $unitPrice * $cartItem->quantity;
                $total = ($unitPrice + $taxAmount) * $cartItem->quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_combination_id' => $combination?->id,
                    'product_name' => $product->name,
                    'product_sku' => $combination?->sku ?? $product->sku,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount * $cartItem->quantity,
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);

                // Reducir stock
                $this->stockService->recordMovement(
                    $product,
                    $cartItem->quantity,
                    'out',
                    'Order #' . $order->order_number,
                    $order->id,
                    $combination
                );
            }

            // Registrar cambio de estado inicial
            $order->statusHistory()->create([
                'status' => 'pending',
                'comment' => 'Orden creada',
                'notify_customer' => false,
            ]);

            // Limpiar carrito
            $cart->items()->delete();
            $cart->update(['coupon_id' => null]);

            // Disparar evento
            event(new OrderCreated($order));

            return $order->load(['items', 'billingAddress', 'shippingAddress']);
        });
    }

    /**
     * Actualiza el estado de una orden
     */
    public function updateStatus(Order $order, string $newStatus, ?string $comment = null, bool $notifyCustomer = true): void
    {
        $oldStatus = $order->current_status;

        $order->update(['current_status' => $newStatus]);

        $order->statusHistory()->create([
            'status' => $newStatus,
            'comment' => $comment,
            'notify_customer' => $notifyCustomer,
            'user_id' => auth()->id(),
        ]);

        event(new OrderStatusChanged($order, $oldStatus, $newStatus));
    }

    /**
     * Genera un número de orden único
     */
    protected function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Cancela una orden y restaura el stock
     */
    public function cancel(Order $order, string $reason = null): void
    {
        if (!in_array($order->current_status, ['pending', 'processing'])) {
            throw new \Exception('No se puede cancelar una orden en estado: ' . $order->current_status);
        }

        DB::transaction(function () use ($order, $reason) {
            // Restaurar stock
            foreach ($order->items as $item) {
                $this->stockService->recordMovement(
                    $item->product,
                    $item->quantity,
                    'in',
                    'Order cancelled: ' . $order->order_number,
                    $order->id,
                    $item->combination
                );
            }

            $this->updateStatus($order, 'cancelled', $reason, true);
        });
    }
}
```

---

## ACCIONES

### AddItemToCart Action

```php
<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductCombination;
use App\Services\Cart\CartService;

class AddItemToCart
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function execute(
        Cart $cart,
        int $productId,
        int $quantity = 1,
        ?int $combinationId = null
    ): void {
        $product = Product::findOrFail($productId);
        $combination = $combinationId ? ProductCombination::findOrFail($combinationId) : null;

        $this->cartService->addItem($cart, $product, $quantity, $combination);
    }
}
```

### ProcessPayment Action

```php
<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Services\Payment\PaymentServiceInterface;
use App\Events\PaymentProcessed;
use App\Events\PaymentFailed;

class ProcessPayment
{
    /**
     * Procesa el pago de una orden
     */
    public function execute(Order $order, PaymentServiceInterface $paymentService, array $paymentData): bool
    {
        try {
            $order->update(['payment_status' => 'processing']);

            $result = $paymentService->process($order, $paymentData);

            if ($result->isSuccessful()) {
                $order->update([
                    'payment_status' => 'completed',
                    'payment_transaction_id' => $result->getTransactionId(),
                ]);

                event(new PaymentProcessed($order, $result));

                return true;
            } else {
                $order->update(['payment_status' => 'failed']);
                event(new PaymentFailed($order, $result->getError()));

                return false;
            }
        } catch (\Exception $e) {
            $order->update(['payment_status' => 'failed']);
            event(new PaymentFailed($order, $e->getMessage()));

            throw $e;
        }
    }
}
```

---

## DTOs

### CartItemDTO

```php
<?php

namespace App\DataTransferObjects;

class CartItemDTO
{
    public function __construct(
        public int $productId,
        public string $productName,
        public string $productSku,
        public ?int $combinationId,
        public ?string $combinationName,
        public int $quantity,
        public float $unitPrice,
        public float $subtotal,
        public ?string $image,
    ) {}

    public static function fromCartItem($cartItem): self
    {
        return new self(
            productId: $cartItem->product_id,
            productName: $cartItem->product->name,
            productSku: $cartItem->combination?->sku ?? $cartItem->product->sku,
            combinationId: $cartItem->product_combination_id,
            combinationName: $cartItem->combination?->display_name,
            quantity: $cartItem->quantity,
            unitPrice: $cartItem->price_snapshot,
            subtotal: $cartItem->price_snapshot * $cartItem->quantity,
            image: $cartItem->product->primary_image,
        );
    }
}
```

---

## EVENTOS Y LISTENERS

### OrderCreated Event

```php
<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}
}
```

### SendOrderConfirmationEmail Listener

```php
<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmationEmail implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        Mail::to($event->order->customer_email)
            ->send(new OrderConfirmation($event->order));
    }
}
```

### Registrar en EventServiceProvider

```php
<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\CreateInvoice;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            SendOrderConfirmationEmail::class,
            CreateInvoice::class,
        ],
    ];
}
```

---

## MIDDLEWARE

### SetLanguage Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLanguage
{
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale', config('app.locale'));

        App::setLocale($locale);

        return $next($request);
    }
}
```

### EnsureCartExists Middleware

```php
<?php

namespace App\Http\Middleware;

use App\Services\Cart\CartService;
use Closure;
use Illuminate\Http\Request;

class EnsureCartExists
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $cart = $this->cartService->getCart(auth()->user());
        $request->merge(['cart' => $cart]);

        view()->share('cart', $cart);

        return $next($request);
    }
}
```

---

## FORM REQUESTS

### AddToCartRequest

```php
<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'combination_id' => ['nullable', 'exists:product_combinations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Debes seleccionar un producto',
            'product_id.exists' => 'El producto seleccionado no existe',
            'quantity.required' => 'Debes indicar la cantidad',
            'quantity.min' => 'La cantidad mínima es 1',
            'quantity.max' => 'La cantidad máxima es 99',
        ];
    }
}
```

### PlaceOrderRequest

```php
<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'billing_address_id' => ['required', 'exists:addresses,id'],
            'shipping_address_id' => ['required', 'exists:addresses,id'],
            'payment_method' => ['required', 'string', 'in:bank_transfer,paypal'],
            'shipping_method' => ['required', 'string'],
            'terms_accepted' => ['required', 'accepted'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
```

---

## COMPONENTES BLADE

### Product Card Component

**app/View/Components/Product/Card.php:**

```php
<?php

namespace App\View\Components\Product;

use App\Models\Product;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(
        public Product $product
    ) {}

    public function render()
    {
        return view('components.product.card');
    }
}
```

**resources/views/components/product/card.blade.php:**

```blade
<div class="group bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <div class="aspect-square overflow-hidden rounded-t-lg bg-gray-100">
            <img
                src="{{ $product->primary_image }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
            >
        </div>
    </a>

    <div class="p-4">
        @if($product->brand)
            <p class="text-xs text-gray-500 uppercase mb-1">{{ $product->brand->name }}</p>
        @endif

        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-primary-600 transition-colors">
                {{ $product->name }}
            </a>
        </h3>

        @if($product->short_description)
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->short_description }}</p>
        @endif

        <div class="flex items-center justify-between mb-3">
            <div class="flex flex-col">
                <span class="text-xl font-bold text-gray-900">
                    {{ $product->base_price_formatted }}
                </span>
                @if($product->tax)
                    <span class="text-xs text-gray-500">IVA incluido</span>
                @endif
            </div>

            @if($product->is_out_of_stock)
                <span class="text-sm text-red-600 font-semibold">Agotado</span>
            @elseif($product->is_low_stock)
                <span class="text-sm text-orange-600">Últimas unidades</span>
            @endif
        </div>

        <button
            type="button"
            onclick="addToCart({{ $product->id }})"
            @disabled($product->is_out_of_stock)
            class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
            @if($product->is_out_of_stock)
                No disponible
            @else
                Añadir al carrito
            @endif
        </button>
    </div>
</div>
```

---

## CONTROLLERS

### ProductController

```php
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function index(Request $request)
    {
        $products = $this->productRepository->paginate(
            config('shop.pagination.products_per_page', 12)
        );

        return view('shop.products.index', compact('products'));
    }

    public function show(string $slug)
    {
        $product = $this->productRepository->findBySlug($slug);

        if (!$product) {
            abort(404);
        }

        // Incrementar contador de vistas
        $product->increment('views_count');

        return view('shop.products.show', compact('product'));
    }
}
```

### CartController

```php
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Actions\Cart\AddItemToCart;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected AddItemToCart $addItemToCart
    ) {}

    public function index(Request $request)
    {
        $cart = $this->cartService->getCart(auth()->user());

        return view('shop.cart.index', compact('cart'));
    }

    public function add(AddToCartRequest $request)
    {
        $cart = $this->cartService->getCart(auth()->user());

        try {
            $this->addItemToCart->execute(
                $cart,
                $request->validated('product_id'),
                $request->validated('quantity', 1),
                $request->validated('combination_id')
            );

            return redirect()->back()->with('success', 'Producto añadido al carrito');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, int $cartItemId)
    {
        $cart = $this->cartService->getCart(auth()->user());

        try {
            $this->cartService->updateQuantity(
                $cart,
                $cartItemId,
                $request->input('quantity')
            );

            return redirect()->back()->with('success', 'Carrito actualizado');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove(int $cartItemId)
    {
        $cart = $this->cartService->getCart(auth()->user());
        $this->cartService->removeItem($cart, $cartItemId);

        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }
}
```

---

## RUTAS

**routes/web.php:**

```php
<?php

use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Productos
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('products.show');

// Categorías
Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Carrito
Route::prefix('carrito')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{cartItem}', [CartController::class, 'update'])->name('update');
    Route::delete('/{cartItem}', [CartController::class, 'remove'])->name('remove');
});

// Checkout (requiere autenticación o guest checkout)
Route::prefix('checkout')->name('checkout.')->middleware(['web'])->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place-order');
    Route::get('/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('confirmation');
});

// Cuenta de usuario (requiere autenticación)
Route::middleware(['auth'])->prefix('mi-cuenta')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/pedidos', [AccountController::class, 'orders'])->name('orders');
    Route::get('/pedidos/{order}', [AccountController::class, 'orderDetail'])->name('orders.show');
    Route::get('/direcciones', [AddressController::class, 'index'])->name('addresses');
});

require __DIR__.'/auth.php';
```

---

**Última actualización:** 19 de diciembre de 2025
