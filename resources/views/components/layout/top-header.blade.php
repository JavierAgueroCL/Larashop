<div class="bg-white border-b border-gray-300">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
 <div class="flex justify-between items-center h-10 text-xs text-gray-600">
 <!-- Left: Contact & Currency -->
 <div class="flex items-center space-x-4">
 <div class="hidden md:flex items-center">
 <svg class="w-4 h-4 mr-1 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
 <span>123-456-7890</span>
 </div>
 <div class="flex items-center cursor-pointer hover:text-primary-500">
 <span>English</span>
 <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
 </div>
 <div class="flex items-center cursor-pointer hover:text-primary-500">
 <span>EUR</span>
 <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
 </div>
 </div>

 <!-- Right: Auth & Links -->
 <div class="flex items-center space-x-4">
 <a href="#" class="hover:text-primary-500 hidden md:block">Compare</a>
 <a href="#" class="hover:text-primary-500 hidden md:block">Wishlist</a>
 @auth
 <a href="{{ route('dashboard') }}" class="hover:text-primary-500 flex items-center">
 <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
 {{ Auth::user()->name }}
 </a>
 @else
 <a href="{{ route('login') }}" class="hover:text-primary-500 flex items-center">
 <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
 Login
 </a>
 @endauth
 </div>
 </div>
 </div>
</div>
