<footer class="bg-secondary text-white mt-auto">
    <!-- Newsletter Section -->
    <div class="bg-primary-500 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-2xl font-bold text-white mb-1">Subscribe Our Newsletter</h3>
                    <p class="text-white text-opacity-80">Subscribe to our newsletter and get 10% off your first purchase</p>
                </div>
                <div class="w-full md:w-1/2">
                    <form class="flex">
                        <input type="email" placeholder="Your Email Address" class="w-full px-4 py-3 rounded-l-md border-none focus:ring-0 text-gray-800">
                        <button type="submit" class="bg-secondary text-white px-6 py-3 rounded-r-md font-bold hover:bg-gray-900 transition-colors uppercase tracking-wide">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <!-- Store Info -->
            <div class="col-span-1">
                <a href="/" class="flex items-center mb-6">
                    <span class="text-2xl font-bold text-white tracking-wider"><span class="text-primary-500">Lara</span>Shop</span>
                </a>
                <p class="text-sm text-gray-400 mb-6 leading-relaxed">
                    If you are going to use of Lorem Ipsum need to be sure there isn't hidden of text.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-primary-500 transition-colors"><span class="sr-only">Facebook</span>FB</a>
                    <a href="#" class="text-gray-400 hover:text-primary-500 transition-colors"><span class="sr-only">Twitter</span>TW</a>
                    <a href="#" class="text-gray-400 hover:text-primary-500 transition-colors"><span class="sr-only">Instagram</span>IG</a>
                    <a href="#" class="text-gray-400 hover:text-primary-500 transition-colors"><span class="sr-only">Youtube</span>YT</a>
                </div>
            </div>

            <!-- Categories -->
            <div class="col-span-1">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">{{ __('Shop') }}</h3>
                <ul class="space-y-2">
                    @foreach($globalCategories->take(5) as $category)
                        <li>
                            <a href="{{ route('products.category', $category->slug) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('products.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold">
                            {{ __('View All') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- My Account -->
            <div class="col-span-1">
                <h3 class="text-lg font-bold text-white mb-6">My Account</h3>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary-500 transition-colors">My Account</a></li>
                    <li><a href="#" class="hover:text-primary-500 transition-colors">Discount</a></li>
                    <li><a href="#" class="hover:text-primary-500 transition-colors">Returns</a></li>
                    <li><a href="#" class="hover:text-primary-500 transition-colors">Orders History</a></li>
                    <li><a href="#" class="hover:text-primary-500 transition-colors">Order Tracking</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-span-1">
                <h3 class="text-lg font-bold text-white mb-6">Contact Info</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-primary-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>123 Street, Old Trafford, New South Wales, London, UK</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <a href="mailto:info@sitename.com" class="hover:text-primary-500">info@sitename.com</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>+ 457 789 789 65</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    
    <div class="bg-gray-900 py-6 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-gray-400 mb-4 md:mb-0">
                &copy; {{ date('Y') }} All Rights Reserved by {{ config('app.name') }}
            </p>
            <div class="flex space-x-2">
                <img src="https://placehold.co/50x30/333/fff?text=Visa" alt="Visa">
                <img src="https://placehold.co/50x30/333/fff?text=MC" alt="MasterCard">
                <img src="https://placehold.co/50x30/333/fff?text=PP" alt="PayPal">
            </div>
        </div>
    </div>
</footer>
