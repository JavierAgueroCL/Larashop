<x-app-layout>
    <x-slot name="meta_title">{{ $product->meta_title ?? $product->name }}</x-slot>
    <x-slot name="meta_description">{{ $product->meta_description ?? $product->short_description }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                
                <!-- Sidebar -->
                <div class="hidden md:block w-1/4">
                    @include('components.shop.sidebar')
                </div>

                <!-- Product Content -->
                <div class="w-full md:w-3/4">
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-300">
                        
                        <!-- Top Section: Details -->
                        <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Product Images -->
                            <div class="space-y-4">
                                <div class="h-96 bg-gray-100 w-full object-cover rounded-lg overflow-hidden border border-gray-200">
                                    @if($product->primary_image)
                                        <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400">
                                            No Image
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Basic Info & Cart -->
                            <div>
                                <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
                                
                                @if($product->brand)
                                    <p class="text-sm text-gray-500 mb-4">{{ __('Brand:') }} <span class="font-semibold text-gray-700">{{ $product->brand->name }}</span></p>
                                @endif

                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <div class="flex items-center text-yellow-400">
                                            @for($i=1; $i<=5; $i++)
                                                @if($i <= round($product->reviews->avg('rating')))
                                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500">({{ $product->reviews->count() }} reviews)</span>
                                    </div>
                                </div>

                                <p class="text-3xl text-indigo-600 font-bold mb-6">{{ $product->base_price_formatted }}</p>

                                @if($product->short_description)
                                    <div class="mb-6 text-gray-600">
                                        {{ $product->short_description }}
                                    </div>
                                @endif

                                <div class="flex items-center space-x-4 mb-8">
                                    <form x-data="{}" action="{{ route('cart.add') }}" method="POST" class="w-full flex gap-4" @submit.prevent="fetch($el.action, {
                                        method: 'POST',
                                        body: new FormData($el),
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(async r => {
                                        if (!r.ok) {
                                            const error = await r.json();
                                            alert('Error adding to cart: ' + (error.message || 'Unknown error'));
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
                                        alert('System Error: ' + e);
                                    })">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="w-24">
                                            <label for="quantity" class="sr-only">Quantity</label>
                                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition duration-300 shadow-md">
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
                                
                                <div class="border-t border-gray-200 pt-4 text-sm text-gray-500">
                                    <p>{{ __('SKU:') }} <span class="text-gray-700">{{ $product->sku }}</span></p>
                                    @if($product->categories->isNotEmpty())
                                        <p>{{ __('Categories:') }} <span class="text-gray-700">{{ $product->categories->pluck('name')->join(', ') }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Section: Description & Reviews -->
                        <div class="border-t border-gray-300 p-8 bg-gray-50/50">
                            <!-- Tabs (Simple visual separation for now) -->
                            <div class="mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 border-b-2 border-indigo-600 inline-block pb-2 mb-6">{{ __('Description') }}</h3>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>

                            <div class="border-t border-gray-300 pt-8">
                                <h3 class="text-2xl font-bold text-gray-900 border-b-2 border-indigo-600 inline-block pb-2 mb-6">{{ __('Customer Reviews') }}</h3>
                                
                                <!-- Write Review Form -->
                                @auth
                                    @if(auth()->user()->hasPurchased($product) && !$product->reviews()->where('user_id', auth()->id())->exists())
                                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
                                            <h4 class="text-lg font-bold mb-4">{{ __('Write a Review') }}</h4>
                                            
                                            @if(session('error'))
                                                <div class="text-red-500 mb-4">{{ session('error') }}</div>
                                            @endif

                                            <form action="{{ route('products.reviews.store', $product->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Rating') }}</label>
                                                    <div class="flex items-center space-x-6">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <label class="flex flex-col items-center cursor-pointer group">
                                                                <input type="radio" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} class="w-6 h-6 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                                <span class="text-sm font-bold mt-1 text-gray-600 group-hover:text-indigo-600">{{ $i }}</span>
                                                            </label>
                                                        @endfor
                                                    </div>
                                                    @error('rating') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div class="mb-4">
                                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Your Review') }}</label>
                                                    <textarea name="comment" id="comment" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                                    @error('comment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 transition duration-300 shadow-md">
                                                    {{ __('Submit Review') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth

                                <!-- Reviews List -->
                                @if($product->reviews->isEmpty())
                                    <p class="text-gray-500 italic">{{ __('No reviews yet. Be the first to write one!') }}</p>
                                @else
                                    <div class="space-y-6">
                                        @foreach($product->reviews as $review)
                                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                                <div class="flex items-center justify-between mb-4">
                                                    <div class="flex items-center">
                                                        <div class="font-bold text-gray-900 mr-4">{{ $review->user->name }}</div>
                                                        <div class="flex text-yellow-400 text-sm">
                                                            @for($i=1; $i<=5; $i++)
                                                                @if($i <= $review->rating)
                                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                                @else
                                                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                                                </div>
                                                <p class="text-gray-700">{{ $review->comment }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
