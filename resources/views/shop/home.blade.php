<x-app-layout>
    
    <!-- Hero Slider -->
    <div class="relative bg-gray-100 h-[500px] flex items-center">
        <div class="absolute inset-0 z-0">
             <img src="https://placehold.co/1920x600/f3f4f6/FF324D?text=New+Collection" alt="Banner" class="w-full h-full object-cover">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="w-full md:w-1/2">
                <h5 class="text-primary-500 font-medium mb-3 uppercase tracking-wider">{{ __('New Arrivals') }}</h5>
                <h1 class="text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ __('Best Prices & Deals') }}</h1>
                <p class="text-gray-600 mb-8 text-lg">{{ __('Discover our exclusive collection of high-quality products. Shop now and get the best deals.') }}</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-primary-500 text-white px-8 py-3 rounded-sm font-semibold uppercase tracking-wide hover:bg-primary-600 transition-colors">
                    {{ __('Shop Now') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex items-center p-6 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="text-primary-500 mr-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-800">{{ __('Free Shipping') }}</h5>
                        <p class="text-sm text-gray-500">{{ __('On all orders over $50') }}</p>
                    </div>
                </div>
                <div class="flex items-center p-6 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="text-primary-500 mr-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-800">{{ __('Secure Payment') }}</h5>
                        <p class="text-sm text-gray-500">{{ __('100% secure payment') }}</p>
                    </div>
                </div>
                <div class="flex items-center p-6 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="text-primary-500 mr-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-800">{{ __('24/7 Support') }}</h5>
                        <p class="text-sm text-gray-500">{{ __('Dedicated support') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exclusive Products -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Exclusive Products') }}</h2>
                <div class="w-16 h-1 bg-primary-500 mx-auto"></div>
            </div>

            @if($featuredProducts->isEmpty())
                <p class="text-center text-gray-500">{{ __('No products available.') }}</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        <x-product.card :product="$product" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Banner Area -->
    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="relative group overflow-hidden">
                    <img src="https://placehold.co/600x300/333/fff?text=New+Season" alt="Banner 1" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center p-8">
                        <span class="text-primary-500 font-bold uppercase mb-2">Super Sale</span>
                        <h3 class="text-white text-3xl font-bold mb-4">New Collection</h3>
                        <a href="#" class="text-white font-semibold hover:text-primary-500 underline">Shop Now</a>
                    </div>
                </div>
                <div class="relative group overflow-hidden">
                    <img src="https://placehold.co/600x300/333/fff?text=Men's+Fashion" alt="Banner 2" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center p-8">
                        <span class="text-primary-500 font-bold uppercase mb-2">New Season</span>
                        <h3 class="text-white text-3xl font-bold mb-4">Sale 40% Off</h3>
                        <a href="#" class="text-white font-semibold hover:text-primary-500 underline">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Arrivals -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ __('New Arrivals') }}</h2>
                <div class="w-16 h-1 bg-primary-500 mx-auto"></div>
            </div>

            @if($newProducts->isEmpty())
                <p class="text-center text-gray-500">{{ __('No new products available.') }}</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($newProducts as $product)
                        <x-product.card :product="$product" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
