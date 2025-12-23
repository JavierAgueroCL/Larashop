@props(['product'])

<div
    class="product_wrap bg-white overflow-hidden group relative border border-gray-300 hover:shadow-lg transition-all duration-300">

    <!-- Image -->
    <div class="product_img relative overflow-hidden h-64">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ $product->primary_image }}" alt="{{ $product->name }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        </a>

        <!-- Actions -->
        <div x-data
            class="product_action_box absolute bottom-4 left-0 right-0 flex justify-center space-x-2 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <form x-data="{}" action="{{ route('cart.add') }}" method="POST" @submit.prevent="fetch($el.action, {                                     method: 'POST',
                                     body: new FormData($el),
                                     headers: {
                                         'Accept': 'application/json',
                                         'X-Requested-With': 'XMLHttpRequest'
                                     }
                                 })
                                 .then(async r => {
                                     if (!r.ok) {
                                         const error = await r.json();
                                         Swal.fire({
                                             icon: 'error',
                                             title: 'Oops...',
                                             text: 'Error adding to cart: ' + (error.message || 'Unknown error')
                                         });
                                         return;
                                     }
                                     return r.json();
                                 })
            .then(data => {
                if (data && data.success) {
                    window.dispatchEvent(new CustomEvent('open-cart', { detail: { html: data.html } }));
                } else if (data) {
                    window.location.href = '{{ route('cart.index') }}';
                }
            })
            .catch(e => {
                console.error(e);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: e.toString()
                });
            })"> @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                    class="h-10 w-10 bg-white text-gray-800 hover:bg-primary-500 hover:text-white rounded-full shadow-md flex items-center justify-center transition-colors"
                    title="Añadir al Carrito">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </button>
            </form>
            <button @click.prevent="$dispatch('open-quick-view', { id: {{ $product->id }} })"
                class="h-10 w-10 bg-white text-gray-800 hover:bg-primary-500 hover:text-white rounded-full shadow-md flex items-center justify-center transition-colors"
                title="Vista Rápida"> <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                    </path>
                </svg>
            </button>
            <button @click.prevent="$dispatch('open-add-to-wishlist', { productId: {{ $product->id }} })"
                class="h-10 w-10 bg-white text-gray-800 hover:bg-primary-500 hover:text-white rounded-full shadow-md flex items-center justify-center transition-colors"
                title="Lista de Deseos">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Info -->
    <div class="product_info p-4 text-center">
        <h6 class="product_title font-medium text-secondary hover:text-primary-500 transition-colors mb-1 truncate">
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
        </h6>
        <div class="product_price">
            @if($product->discount_price)
                <span class="price font-bold text-primary-500">{{ $product->discount_price_formatted }}</span>
                <del class="text-sm text-gray-400 ml-2">{{ $product->base_price_formatted }}</del>
            @else
                <span class="price font-bold text-primary-500">{{ $product->base_price_formatted }}</span>
            @endif
        </div>

        <div class="rating_wrap flex justify-center mt-2">
            <div class="rating">
                <div class="product_rate flex text-yellow-400 text-xs">
                    {{-- Loop stars --}}
                    @php $avgRating = round($product->reviews->avg('rating')); @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $avgRating)
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @else
                            <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endif
                    @endfor
                </div>
            </div>
            <span class="rating_num text-xs text-gray-500 ml-1">({{ $product->reviews->count() }})</span>
        </div>
    </div>
</div>