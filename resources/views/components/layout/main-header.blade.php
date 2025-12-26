<header class="bg-white shadow-md sticky top-0 z-50">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
 <div class="flex justify-between items-center h-20">
             <!-- Logo -->
             <div class="flex-shrink-0 flex items-center">
                 <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800 tracking-wider">
                     @if($logo = get_setting('shop_logo'))
                         <img src="{{ asset($logo) }}" alt="{{ get_setting('shop_name') }}" class="h-12 w-auto">
                     @else
                         {{ get_setting('shop_name') }}
                     @endif
                 </a>
             </div>
 <!-- Search (Hidden on Mobile) -->
 <div class="hidden md:flex flex-1 max-w-lg mx-8">
 <form action="{{ route('products.index') }}" method="GET" class="w-full relative">
 <div class="flex">
 <select class="h-11 border-gray-300 border-r-0 rounded-l-md text-sm text-gray-600 focus:ring-0 focus:border-gray-300 bg-gray-50">
 <option>Todas las categorías</option>
 @foreach($globalCategories as $category)
 <option value="{{ $category->slug }}">{{ $category->name }}</option>
 @endforeach
 </select>
 <input type="text" name="search" placeholder="Buscar Producto..." class="w-full h-11 border-gray-300 focus:ring-primary-500 focus:border-primary-500 text-sm">
 <button type="submit" class="h-11 w-12 bg-primary-500 hover:bg-primary-600 text-white rounded-r-md flex items-center justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
 </button>
 </div>
 </form>
 </div>

 <!-- Actions -->
 <div class="flex items-center space-x-6">
 <!-- User (Mobile) -->
 <a href="#" class="md:hidden text-gray-600 hover:text-primary-500">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
 </a>

                 <!-- Wishlist -->
                 <a href="{{ route('wishlist.index') }}" class="relative text-gray-600 hover:text-primary-500">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                     <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">{{ $wishlistCount ?? 0 }}</span>
                 </a>
 <!-- Cart -->
 <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary-500">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
 <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">
 {{ $cartCount ?? 0 }}
 </span>
 </a>
 </div>
 </div>
 </div>
</header>
