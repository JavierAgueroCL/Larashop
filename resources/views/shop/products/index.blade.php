<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shop') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                
                <!-- Sidebar Filters -->
                @include('components.shop.sidebar')

                <!-- Product Grid -->
                <div class="w-full md:w-3/4">
                    
                    <!-- Sorting & count -->
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
                        </p>
                        <div class="flex items-center">
                            <label for="sort" class="mr-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Sort by:') }}</label>
                            <select id="sort" onchange="window.location.href=this.value" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>

                    @if($products->isEmpty())
                        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-sm text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-gray-400 mb-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('No products found matching your selection.') }}</p>
                            <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">{{ __('Clear filters') }}</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <div class="h-48 bg-gray-200 dark:bg-gray-700 w-full object-cover">
                                             @if($product->primary_image)
                                                <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full text-gray-400">
                                                    No Image
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 truncate">
                                            <a href="{{ route('products.show', $product->slug) }}">
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <div class="flex justify-between items-center">
                                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">
                                                {{ $product->base_price_formatted }}
                                            </span>
                                        </div>
                                         <a href="{{ route('products.show', $product->slug) }}" class="mt-4 block text-center w-full bg-gray-900 dark:bg-gray-700 text-white py-2 rounded-md hover:bg-gray-800 dark:hover:bg-gray-600 text-sm">
                                            {{ __('View Details') }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
