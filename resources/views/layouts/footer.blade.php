<footer class="bg-gray-50 text-gray-700 mt-auto border-t border-gray-200">
 <!-- Newsletter Section -->
 <div class="bg-primary-500 py-12">
 <div class="max-w-[1350px] mx-auto px-4 sm:px-6 lg:px-8">
 <div class="flex flex-col md:flex-row items-center justify-between">
 <div class="mb-4 md:mb-0">
 <h3 class="text-2xl font-bold text-white mb-1">Suscríbete a nuestro boletín</h3>
 <p class="text-white text-opacity-80">Suscríbete a nuestro boletín y obtén un 10% de descuento en tu primera compra</p>
 </div>
            <div class="w-full md:w-1/2">
                @if(session('success') && str_contains(session('success'), 'newsletter'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @elseif(session('info') && str_contains(session('info'), 'newsletter'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                         <span class="block sm:inline">{{ session('info') }}</span>
                    </div>
                @else
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-2 sm:gap-0">
                        @csrf
                        <input type="email" name="email" placeholder="Tu correo electrónico" required class="w-full px-4 py-3 rounded-md sm:rounded-l-md sm:rounded-r-none border-none focus:ring-0 text-gray-800" value="{{ old('email') }}">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md sm:rounded-l-none sm:rounded-r-md font-bold hover:bg-indigo-700 transition-colors uppercase tracking-wide">Suscribirse</button>
                    </form>
                    @error('email')
                        <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @endif
            </div>
 </div>
 </div>
 </div>

 <!-- Main Footer -->
 <div class="max-w-[1350px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
 <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
 
             <!-- Store Info -->
             <div class="col-span-1">
                 <a href="/" class="flex items-center mb-6">
                     <span class="text-2xl font-bold text-gray-900 tracking-wider">{{ get_setting('shop_name') }}</span>
                 </a>
                 <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                     {{ get_setting('shop_description') }}
                 </p>
                                 <div class="flex space-x-4">
                                     <a href="{{ get_setting('social_facebook', '#') }}" class="text-gray-400 hover:text-primary-500 transition-colors" target="_blank">FB</a>
                                     <a href="{{ get_setting('social_twitter', '#') }}" class="text-gray-400 hover:text-primary-500 transition-colors" target="_blank">TW</a>
                                     <a href="{{ get_setting('social_instagram', '#') }}" class="text-gray-400 hover:text-primary-500 transition-colors" target="_blank">IG</a>
                                     <a href="{{ get_setting('social_youtube', '#') }}" class="text-gray-400 hover:text-primary-500 transition-colors" target="_blank">YT</a>
                                 </div>             </div>
 
             <!-- Categories -->
             <div class="col-span-1">
                 <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">{{ __('Tienda') }}</h3>
                 <ul class="space-y-2">
                     @foreach($globalCategories->take(5) as $category)
                         <li>
                             <a href="{{ route('products.category', $category->slug) }}" class="text-sm text-gray-600 hover:text-indigo-600">
                                 {{ $category->name }}
                             </a>
                         </li>
                     @endforeach
                     <li>
                         <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-indigo-600 font-semibold">
                             {{ __('Ver Todo') }}
                         </a>
                     </li>
                 </ul>
             </div>
 
                         <!-- My Account -->
                         <div class="col-span-1">
                             @php $myAccountMenu = get_menu('footer_my_account'); @endphp
                             <h3 class="text-lg font-bold text-gray-900 mb-6">{{ $myAccountMenu ? $myAccountMenu->name : 'Mi Cuenta' }}</h3>
                             <ul class="space-y-3 text-sm text-gray-600">
                                 @if($myAccountMenu)
                                     @foreach($myAccountMenu->items as $item)
                                         <li><a href="{{ $item->link }}" class="hover:text-primary-500 transition-colors" target="{{ $item->target }}">{{ $item->title }}</a></li>
                                     @endforeach
                                 @endif
                             </ul>
                         </div> 
            <!-- Useful Links & Contact -->
            <div class="col-span-1">
                @php $usefulLinksMenu = get_menu('footer_useful_links'); @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-6">{{ $usefulLinksMenu ? $usefulLinksMenu->name : 'Información' }}</h3>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    @if($usefulLinksMenu)
                        @foreach($usefulLinksMenu->items as $item)
                            <li><a href="{{ $item->link }}" class="hover:text-primary-500 transition-colors" target="{{ $item->target }}">{{ $item->title }}</a></li>
                        @endforeach
                    @endif
                </ul>

                <ul class="space-y-4 text-sm text-gray-600">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-primary-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <span>{{ get_setting('shop_address') }}</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <a href="mailto:{{ get_setting('shop_email') }}" class="hover:text-primary-500">{{ get_setting('shop_email') }}</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ get_setting('shop_phone') }}</span>
                    </li>
                </ul>
            </div>
 
         </div>
     </div>
     
     <div class="bg-white py-6 border-t border-gray-200">
         <div class="max-w-[1350px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
             <p class="text-sm text-gray-500 mb-4 md:mb-0">
                 &copy; {{ date('Y') }} Todos los derechos reservados por {{ get_setting('shop_name') }}
             </p> <div class="flex space-x-2 grayscale opacity-60">
 <img src="https://placehold.co/50x30/eee/666?text=Visa" alt="Visa">
 <img src="https://placehold.co/50x30/eee/666?text=MC" alt="MasterCard">
 <img src="https://placehold.co/50x30/eee/666?text=PP" alt="PayPal">
 </div>
 </div>
 </div>
</footer>