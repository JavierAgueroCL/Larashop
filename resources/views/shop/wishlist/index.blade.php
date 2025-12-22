<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Wishlists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Create New Wishlist Form -->
            <div class="mb-8 bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Create New Wishlist') }}</h3>
                <form action="{{ route('wishlist.store') }}" method="POST" class="flex items-end gap-4">
                    @csrf
                    <div class="flex-grow">
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Wishlist Name') }}</label>
                        <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="e.g., Birthday Gift Ideas">
                    </div>
                    <div class="flex items-center mb-2">
                        <input id="is_public" name="is_public" type="checkbox" value="1" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="is_public" class="ml-2 block text-sm text-gray-900">
                            {{ __('Public') }}
                        </label>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        {{ __('Create') }}
                    </button>
                </form>
            </div>

            <!-- Wishlists Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishlists as $list)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow duration-200 border border-gray-100">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        <a href="{{ route('wishlist.show', $list->id) }}" class="hover:text-primary-600">
                                            {{ $list->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $list->items_count }} {{ Str::plural('item', $list->items_count) }}
                                    </p>
                                </div>
                                @if($list->is_default)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ __('Default') }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
                                <a href="{{ route('wishlist.show', $list->id) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    {{ __('View Items') }} &rarr;
                                </a>
                                
                                <div class="flex items-center space-x-3">
                                    @if(!$list->is_default)
                                        <form action="{{ route('wishlist.destroy', $list->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this wishlist?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Delete Wishlist">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>