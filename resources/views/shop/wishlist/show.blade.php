<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $wishlist->name }}
            </h2>
            <a href="{{ route('wishlist.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; {{ __('Back to Wishlists') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Rename / Edit Form -->
            <div class="mb-8 bg-white p-6 rounded-lg shadow-sm" x-data="{ editing: false }">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-sm text-gray-500">{{ $wishlist->is_public ? 'Public' : 'Private' }} Wishlist</span>
                        <div class="mt-1 flex items-center gap-2">
                             <h3 class="text-lg font-medium text-gray-900">{{ $wishlist->name }}</h3>
                             <button @click="editing = !editing" class="text-gray-400 hover:text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                             </button>
                        </div>
                    </div>
                    @if($wishlist->is_public)
                         <button onclick="navigator.clipboard.writeText('{{ route('wishlist.show', $wishlist->id) }}'); Swal.fire({ icon: 'success', title: 'Link copied!', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            Share
                         </button>
                    @endif
                </div>

                <form x-show="editing" action="{{ route('wishlist.update', $wishlist->id) }}" method="POST" class="mt-4 pt-4 border-t border-gray-100 flex items-end gap-4">
                    @csrf
                    @method('PUT')
                    <div class="flex-grow">
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Wishlist Name') }}</label>
                        <input type="text" name="name" id="name" value="{{ $wishlist->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    </div>
                    <div class="flex items-center mb-2">
                        <input id="is_public" name="is_public" type="checkbox" value="1" {{ $wishlist->is_public ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="is_public" class="ml-2 block text-sm text-gray-900">
                            {{ __('Public') }}
                        </label>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                        {{ __('Save') }}
                    </button>
                </form>
            </div>

            @if($wishlistItems->isEmpty())
                <div class="bg-white p-12 rounded-lg shadow-md text-center">
                    <p class="text-gray-500 mb-8">{{ __('This wishlist is empty.') }}</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-500 hover:bg-primary-600 transition-colors">
                        {{ __('Find Products') }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($wishlistItems as $item)
                        <div class="relative group">
                            <!-- Product Card -->
                            <x-product.card :product="$item->product" />
                            
                            <!-- Remove Button Override -->
                            <form action="{{ route('wishlist.remove_item', ['wishlist' => $wishlist->id, 'product' => $item->product->id]) }}" method="POST" class="absolute top-2 right-2 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white rounded-full p-1 shadow hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors" title="Remove from this list">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
