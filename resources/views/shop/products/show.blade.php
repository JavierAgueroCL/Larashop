<x-app-layout>
    <x-slot name="meta_title">{{ $product->meta_title ?? $product->name }}</x-slot>
    <x-slot name="meta_description">{{ $product->meta_description ?? $product->short_description }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                
                <!-- Sidebar -->
                <div class="hidden md:block">
                    @include('components.shop.sidebar')
                </div>

                <!-- Product Details -->
                <div class="w-full md:w-3/4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Product Images -->
                    <div class="space-y-4">
                         <div class="h-96 bg-gray-200 dark:bg-gray-700 w-full object-cover rounded-lg overflow-hidden">
                            @if($product->primary_image)
                                <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <!-- Thumbnails (future implementation) -->
                    </div>

                    <!-- Product Details -->
                    <div>
                        <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                        
                        @if($product->brand)
                            <p class="text-sm text-gray-500 mb-4">{{ __('Brand:') }} <span class="font-semibold">{{ $product->brand->name }}</span></p>
                        @endif

                        <p class="text-3xl text-indigo-600 dark:text-indigo-400 font-bold mb-6">{{ $product->base_price_formatted }}</p>

                        <div class="prose dark:prose-invert mb-8">
                            {!! nl2br(e($product->description)) !!}
                        </div>

                        <div class="flex items-center space-x-4 mb-8">
                             <form action="{{ route('cart.add') }}" method="POST" class="w-full flex gap-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="w-24">
                                    <label for="quantity" class="sr-only">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <button type="submit" class="flex-1 bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition duration-300">
                                    {{ __('Add to Cart') }}
                                </button>
                             </form>
                        </div>
                        
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                                <a href="{{ route('cart.index') }}" class="font-bold underline ml-2">View Cart</a>
                            </div>
                        @endif
                        
                        <div class="border-t pt-4 text-sm text-gray-500">
                            <p>{{ __('SKU:') }} {{ $product->sku }}</p>
                            @if($product->categories->isNotEmpty())
                                <p>{{ __('Categories:') }} {{ $product->categories->pluck('name')->join(', ') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>