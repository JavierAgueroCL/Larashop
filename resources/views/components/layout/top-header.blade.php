<div class="bg-white border-b border-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-10 text-xs text-gray-600">
            <!-- Left: Contact & Currency -->
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center">
                    <svg class="w-4 h-4 mr-1 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                    <span>123-456-7890</span>
                </div>
                
                <!-- Language Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center cursor-pointer hover:text-primary-500 focus:outline-none">
                        <span>{{ app()->getLocale() == 'es' ? 'Español' : (app()->getLocale() == 'en' ? 'English' : 'Language') }}</span>
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute z-[100] mt-2 w-32 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                        <a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Español</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
                    </div>
                </div>

                <!-- Currency Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center cursor-pointer hover:text-primary-500 focus:outline-none">
                        <span>{{ session('currency', 'CLP') }}</span>
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute z-[100] mt-2 w-32 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                        <a href="{{ route('currency.switch', 'CLP') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CLP</a>
                        <a href="{{ route('currency.switch', 'USD') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">USD</a>
                    </div>
                </div>
            </div>

            <!-- Right: Auth & Links -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('wishlist.index') }}" class="hover:text-primary-500 hidden md:block">Wishlist</a>
                @auth
                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center hover:text-primary-500 focus:outline-none">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ Auth::user()->name }}
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-[100] mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Dashboard') }}</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Profile') }}</a>
                            
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hover:text-primary-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Login
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('register') }}" class="hover:text-primary-500">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>