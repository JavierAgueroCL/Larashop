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
                                    <p class="text-sm text-gray-500 mb-4">{{ __('Marca:') }} <span class="font-semibold text-gray-700">{{ $product->brand->name }}</span></p>
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
                                        <span class="ml-2 text-sm text-gray-500">({{ $product->reviews->count() }} reseñas)</span>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    @if($product->discount_price)
                                        <p class="text-3xl text-indigo-600 font-bold inline-block">{{ $product->discount_price_formatted }}</p>
                                        <del class="text-xl text-gray-400 ml-2 inline-block">{{ $product->base_price_formatted }}</del>
                                    @else
                                        <p class="text-3xl text-indigo-600 font-bold">{{ $product->base_price_formatted }}</p>
                                    @endif
                                </div>

                                @if($product->short_description)
                                    <div class="mb-6 text-gray-600">
                                        {{ $product->short_description }}
                                    </div>
                                @endif

                                <div class="flex items-center space-x-4 mb-8" x-data>
                                    <form action="{{ route('cart.add') }}" method="POST" class="w-full flex gap-4" @submit.prevent="fetch($el.action, {
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
                                    })">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="w-24">
                                            <label for="quantity" class="sr-only">Quantity</label>
                                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition duration-300 shadow-md">
                                            {{ __('Añadir al Carrito') }}
                                        </button>
                                    </form>

                                    <!-- Wishlist Button -->
                                    <button type="button" 
                                            @click.prevent="$dispatch('open-add-to-wishlist', { productId: {{ $product->id }} })"
                                            class="px-4 py-3 rounded-lg border border-gray-300 text-gray-500 hover:text-red-500 hover:border-red-500 hover:bg-red-50 transition duration-300 shadow-sm flex items-center justify-center" 
                                            title="Add to Wishlist">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    </button>
                                </div>
                                
                                @if(session('success'))
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                        <span class="block sm:inline">{{ session('success') }}</span>
                                        <a href="{{ route('cart.index') }}" class="font-bold underline ml-2">Ver Carrito</a>
                                    </div>
                                @endif
                                
                                <div class="border-t border-gray-200 pt-4 text-sm text-gray-500">
                                    <p>{{ __('SKU:') }} <span class="text-gray-700">{{ $product->sku }}</span></p>
                                    @if($product->categories->isNotEmpty())
                                        <p>{{ __('Categorías:') }} 
                                            <span class="text-gray-700">
                                                @foreach($product->categories as $category)
                                                    <a href="{{ route('products.category', $category->slug) }}" class="hover:text-indigo-600 hover:underline">
                                                        {{ $category->name }}
                                                    </a>@if(!$loop->last), @endif
                                                @endforeach
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Section: Tabs -->
                        <div class="border-t border-gray-300 p-8 bg-gray-50/50" x-data="{ activeTab: 'description' }">
                            <!-- Tab Headers -->
                            <div class="mb-8 border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                                    <button @click.prevent="activeTab = 'description'"
                                        :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'description', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'description' }"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-wide transition-colors">
                                        {{ __('Descripción') }}
                                    </button>

                                    @if($product->specifications->isNotEmpty())
                                        <button @click.prevent="activeTab = 'specifications'"
                                            :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'specifications', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'specifications' }"
                                            class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-wide transition-colors">
                                            {{ __('Especificaciones') }}
                                        </button>
                                    @endif

                                    <button @click.prevent="activeTab = 'reviews'"
                                        :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'reviews', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reviews' }"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-wide transition-colors">
                                        {{ __('Reseñas de Clientes') }} ({{ $product->reviews->count() }})
                                    </button>

                                    <button @click.prevent="activeTab = 'guarantee'"
                                        :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'guarantee', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'guarantee' }"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-wide transition-colors">
                                        {{ __('Garantía') }}
                                    </button>
                                </nav>
                            </div>

                            <!-- Description Tab -->
                            <div x-show="activeTab === 'description'" x-transition:enter.opacity.duration.300ms>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>

                            <!-- Specifications Tab -->
                            @if($product->specifications->isNotEmpty())
                                <div x-show="activeTab === 'specifications'" x-transition:enter.opacity.duration.300ms>
                                    <div class="space-y-8">
                                        @foreach($product->specifications->groupBy('attribute_section') as $section => $specs)
                                            <div>
                                                <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">{{ $section }}</h4>
                                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <tbody class="divide-y divide-gray-200">
                                                            @foreach($specs as $spec)
                                                                <tr class="even:bg-gray-50">
                                                                    <td class="px-6 py-4 text-sm font-medium text-gray-500 w-1/3">{{ $spec->attribute_name }}</td>
                                                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $spec->attribute_value }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Reviews Tab -->
                            <div x-show="activeTab === 'reviews'" x-transition:enter.opacity.duration.300ms>
                                
                                <!-- Write Review Form -->
                                @auth
                                    @if(auth()->user()->hasPurchased($product) && !$product->reviews()->where('user_id', auth()->id())->exists())
                                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
                                            <h4 class="text-lg font-bold mb-4">{{ __('Escribir una Reseña') }}</h4>
                                            
                                            @if(session('error'))
                                                <div class="text-red-500 mb-4">{{ session('error') }}</div>
                                            @endif

                                            <form action="{{ route('products.reviews.store', $product->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Valoración') }}</label>
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
                                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tu Reseña') }}</label>
                                                    <textarea name="comment" id="comment" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                                    @error('comment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 transition duration-300 shadow-md">
                                                    {{ __('Enviar Reseña') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth

                                <!-- Reviews List -->
                                @if($product->reviews->isEmpty())
                                    <p class="text-gray-500 italic">{{ __('No hay reseñas todavía. ¡Sé el primero en escribir una!') }}</p>
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

                            <!-- Guarantee Tab -->
                            <div x-show="activeTab === 'guarantee'" x-transition:enter.opacity.duration.300ms>
                                <div class="prose max-w-none text-gray-700">
                                    @if($product->guarantee)
                                        {!! nl2br(e($product->guarantee)) !!}
                                    @else
                                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-blue-700">
                                                        {{ __('Se aplica la garantía estándar del fabricante a este producto.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
