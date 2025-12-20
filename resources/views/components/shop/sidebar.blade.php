<div class="w-full md:w-1/4">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        
        <!-- Search -->
        <div class="mb-6">
            <form action="{{ route('products.index') }}" method="GET">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                <div class="flex">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-l-md shadow-sm" placeholder="Product name...">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Categories -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-900 dark:text-gray-100 mb-3">{{ __('Categories') }}</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('products.index') }}" class="{{ !request('category') && !isset($category) ? 'text-indigo-600 font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-500' }}">
                        {{ __('All Categories') }}
                    </a>
                </li>
                @foreach($globalCategories as $cat)
                    <li>
                        <a href="{{ route('products.category', $cat->slug) }}" class="{{ (request('category') == $cat->slug || (isset($category) && $category->slug == $cat->slug)) ? 'text-indigo-600 font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-indigo-500' }}">
                            {{ $cat->name }}
                        </a>
                        @if($cat->children->isNotEmpty())
                            <ul class="ml-4 mt-1 space-y-1">
                                @foreach($cat->children as $child)
                                    <li>
                                        <a href="{{ route('products.category', $child->slug) }}" class="{{ (request('category') == $child->slug || (isset($category) && $category->slug == $child->slug)) ? 'text-indigo-600 font-bold' : 'text-sm text-gray-500 dark:text-gray-500 hover:text-indigo-500' }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Clear Filters -->
        @if(request()->has('search') || request()->has('category'))
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('products.index') }}" class="text-sm text-red-500 hover:text-red-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    {{ __('Clear All Filters') }}
                </a>
            </div>
        @endif

    </div>
</div>
