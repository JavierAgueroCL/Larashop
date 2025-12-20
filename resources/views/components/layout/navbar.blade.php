<nav class="bg-white border-b border-gray-200 hidden md:block">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-8 h-12">
            
            <!-- Browse Categories Button -->
            <div class="relative group">
                <button class="flex items-center text-white bg-primary-500 px-4 h-12 font-semibold uppercase text-sm tracking-wide">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    Browse Categories
                </button>
                <!-- Dropdown (Simplified) -->
                <div class="absolute left-0 top-full w-56 bg-white shadow-lg border border-gray-100 hidden group-hover:block z-50">
                    <ul class="py-2">
                        @foreach($globalCategories as $category)
                            <li>
                                <a href="{{ route('products.category', $category->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:text-primary-500 hover:bg-gray-50">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Main Menu -->
            <div class="flex space-x-8">
                <a href="{{ route('home') }}" class="text-sm font-medium text-primary-500 uppercase tracking-wide">Home</a>
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary-500 uppercase tracking-wide">Shop</a>
                <a href="{{ route('pages.show', 'blog') }}" class="text-sm font-medium text-gray-700 hover:text-primary-500 uppercase tracking-wide">Blog</a>
                <a href="{{ route('pages.show', 'contact') }}" class="text-sm font-medium text-gray-700 hover:text-primary-500 uppercase tracking-wide">Contact Us</a>
            </div>

        </div>
    </div>
</nav>
