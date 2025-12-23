<div class="flex-1 py-6 overflow-y-auto px-4 sm:px-6">
    <div class="flow-root">
        <ul role="list" class="-my-6 divide-y divide-gray-200">
            @forelse($cart->items as $item)
                <li class="py-6 flex">
                    <div class="flex-shrink-0 w-24 h-24 border border-gray-200 rounded-md overflow-hidden">
                        <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name }}" class="w-full h-full object-center object-cover">
                    </div>

                    <div class="ml-4 flex-1 flex flex-col">
                        <div>
                            <div class="flex justify-between text-base font-medium text-gray-900">
                                <h3>
                                    <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                </h3>
                                <p class="ml-4">{{ number_format($item->price_snapshot, 0, ',', '.') }} $</p>
                            </div>
                            @if($item->combination)
                                <p class="mt-1 text-sm text-gray-500">{{ $item->combination->name }}</p>
                            @endif
                        </div>
                        <div class="flex-1 flex items-end justify-between text-sm">
                            <p class="text-gray-500">{{ __('Cant.') }} {{ $item->quantity }}</p>

                            <div class="flex">
                                <button type="button" 
                                    @click="fetch('{{ route('cart.remove', $item->id) }}', {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(r => r.json())
                                    .then(data => {
                                        if (data.success) {
                                            window.dispatchEvent(new CustomEvent('open-cart', { detail: { html: data.html } }));
                                            // Update cart count badge if exists (optional but good)
                                            // const badge = document.querySelector('.cart-count-badge');
                                            // if(badge) badge.innerText = data.cartCount;
                                        }
                                    })" 
                                    class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Eliminar') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="py-6 text-center text-gray-500">
                    {{ __('Tu carrito está vacío.') }}
                </li>
            @endforelse
        </ul>
    </div>
</div>

@if($cart->items->isNotEmpty())
    <div class="border-t border-gray-200 py-6 px-4 sm:px-6">
        <div class="flex justify-between text-base font-medium text-gray-900">
            <p>{{ __('Subtotal') }}</p>
            <p>{{ number_format($totals['subtotal'], 0, ',', '.') }} $</p>
        </div>
        <p class="mt-0.5 text-sm text-gray-500">{{ __('Envío e impuestos calculados al finalizar la compra.') }}</p>
        <div class="mt-6">
            <a href="{{ route('checkout.index') }}" class="flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                {{ __('Finalizar Compra') }}
            </a>
        </div>
        <div class="mt-6 flex justify-center text-sm text-center text-gray-500">
            <p>
                {{ __('o') }} <a href="{{ route('cart.index') }}" class="text-indigo-600 font-medium hover:text-indigo-500">
                    {{ __('Ver Carrito') }}<span aria-hidden="true"> &rarr;</span>
                </a>
            </p>
        </div>
    </div>
@endif