@props(['product'])

<div class="product_wrap bg-white overflow-hidden group relative border border-gray-100 hover:shadow-lg transition-all duration-300">
    
    <!-- Image -->
    <div class="product_img relative overflow-hidden h-64">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        </a>
        
        <!-- Actions -->
        <div class="product_action_box absolute bottom-4 left-0 right-0 flex justify-center space-x-2 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="h-10 w-10 bg-white text-gray-800 hover:bg-primary-500 hover:text-white rounded-full shadow-md flex items-center justify-center transition-colors" title="Add to Cart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </button>
            </form>
            <a href="#" class="h-10 w-10 bg-white text-gray-800 hover:bg-primary-500 hover:text-white rounded-full shadow-md flex items-center justify-center transition-colors" title="Quick View">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </a>
            <a href="#" class="h-10 w-10 bg-white text-gray-800 hover:bg-primary-500 hover:text-white rounded-full shadow-md flex items-center justify-center transition-colors" title="Wishlist">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </a>
        </div>
    </div>

    <!-- Info -->
    <div class="product_info p-4 text-center">
        <h6 class="product_title font-medium text-secondary hover:text-primary-500 transition-colors mb-1 truncate">
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
        </h6>
        <div class="product_price">
            <span class="price font-bold text-primary-500">{{ $product->base_price_formatted }}</span>
            {{-- <del class="text-sm text-gray-400 ml-2">$55.00</del> --}}
        </div>
        
        <div class="rating_wrap flex justify-center mt-2">
            <div class="rating">
                <div class="product_rate flex text-yellow-400 text-xs">
                    {{-- Loop stars --}}
                    @for($i=0; $i<5; $i++)
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>
            <span class="rating_num text-xs text-gray-500 ml-1">(25)</span>
        </div>
    </div>
</div>
