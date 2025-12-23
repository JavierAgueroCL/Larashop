<x-app-layout>
    <style>
        .hero-slider-height {
            height: {{ get_setting('hero_slider_height_mobile', 500) }}px;
        }
        @media (min-width: 768px) {
            .hero-slider-height {
                height: {{ get_setting('hero_slider_height_desktop', 750) }}px;
            }
        }
    </style>

    <!-- Hero Slider (Full Width) -->
    <div class="relative group"
        x-data="{ activeSlide: 0, slides: {{ $sliders->count() }}, timer: null }"
        x-init="timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 5000)">
        <div class="relative hero-slider-height overflow-hidden shadow-sm">
            @foreach($sliders as $index => $slider)
                <div class="absolute inset-0 transition-opacity duration-700 ease-in-out bg-cover bg-center flex items-center"
                    x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    style="background-image: url('{{ $slider->background_image_url ?? $slider->image_url }}');">

                    <div class="absolute inset-0 bg-black/10"></div> <!-- Overlay -->

                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-2xl text-white">
                            @if($slider->subtitle)
                                <h5
                                    class="text-primary-500 font-bold mb-2 uppercase tracking-wider bg-white/90 inline-block px-2 py-1 rounded text-sm">
                                    {{ $slider->subtitle }}
                                </h5>
                            @endif
                            @if($slider->title)
                                <h1 class="text-4xl md:text-7xl font-extrabold mb-4 leading-tight drop-shadow-md">
                                    {{ $slider->title }}
                                </h1>
                            @endif
                            @if($slider->description)
                                <p class="text-lg md:text-2xl mb-8 text-gray-100 drop-shadow-md">
                                    {{ $slider->description }}
                                </p>
                            @endif
                            @if($slider->button_text && $slider->button_url)
                                <a href="{{ $slider->button_url }}"
                                    class="inline-block bg-primary-600 border border-transparent rounded-md py-3 px-8 font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors shadow-lg">
                                    {{ $slider->button_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Slider Controls -->
        <div class="absolute inset-0 flex items-center justify-between p-4 pointer-events-none z-10">
            <button
                @click="clearInterval(timer); activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1; timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 5000)"
                class="pointer-events-auto bg-white/20 hover:bg-white/90 text-white hover:text-primary-600 p-3 rounded-full backdrop-blur-md transition-all duration-300 shadow-xl border border-white/30 group-hover:opacity-100 opacity-0 md:opacity-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                    </path>
                </svg>
            </button>
            <button
                @click="clearInterval(timer); activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1; timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 5000)"
                class="pointer-events-auto bg-white/20 hover:bg-white/90 text-white hover:text-primary-600 p-3 rounded-full backdrop-blur-md transition-all duration-300 shadow-xl border border-white/30 group-hover:opacity-100 opacity-0 md:opacity-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Indicators -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-3 z-10">
            @foreach($sliders as $index => $slider)
                <button @click="activeSlide = {{ $index }}"
                    :class="{'bg-primary-600 w-10': activeSlide === {{ $index }}, 'bg-white/40 w-3': activeSlide !== {{ $index }}}"
                    class="h-3 rounded-full transition-all duration-300 shadow-lg border border-white/20 hover:bg-white/80"
                    title="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
    </div>

    <!-- Main Content Area: Sidebar & Content -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Vertical Sidebar (Categories) -->
                <aside class="hidden lg:block w-1/4">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-md overflow-hidden h-fit">
                        <div class="bg-primary-500 text-white px-5 py-3 font-bold uppercase tracking-wide">
                            {{ __('Categorías') }}
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @foreach($globalCategories as $category)
                                <li>
                                    <a href="{{ route('products.category', $category->slug) }}"
                                        class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors flex justify-between items-center group">
                                        <span>{{ $category->name }}</span>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>

                <!-- Features & Products -->
                <div class="w-full lg:w-3/4 space-y-12">
                    <!-- Features -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center p-4 bg-white shadow-sm border border-gray-100 rounded-lg">
                            <div class="text-primary-500 mr-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800">{{ get_setting('feature_1_title', 'Envío Gratis') }}</h5>
                                <p class="text-xs text-gray-500">
                                    {{ get_setting('feature_1_subtitle', 'En pedidos superiores a $50') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-white shadow-sm border border-gray-100 rounded-lg">
                            <div class="text-primary-500 mr-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800">{{ get_setting('feature_2_title', 'Pago Seguro') }}</h5>
                                <p class="text-xs text-gray-500">{{ get_setting('feature_2_subtitle', 'Pago 100% seguro') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-white shadow-sm border border-gray-100 rounded-lg">
                            <div class="text-primary-500 mr-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800">{{ get_setting('feature_3_title', 'Soporte 24/7') }}</h5>
                                <p class="text-xs text-gray-500">{{ get_setting('feature_3_subtitle', 'Soporte dedicado') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Products -->
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">{{ __('Productos Destacados') }}</h2>
                            <a href="{{ route('products.index') }}"
                                class="text-sm font-semibold text-primary-600 hover:text-primary-500">Ver Todo</a>
                        </div>

                        @if($featuredProducts->isEmpty())
                            <p class="text-center text-gray-500">{{ __('No hay productos disponibles.') }}</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach($featuredProducts as $product)
                                    <x-product.card :product="$product" />
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Area -->
    @if($banners->count() > 0)
        <div class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($banners as $banner)
                        <div class="relative group overflow-hidden rounded-lg shadow-sm">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                class="w-full h-48 md:h-64 object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/40 flex flex-col justify-center p-8">
                                @if($banner->subtitle)
                                    <span class="text-primary-400 font-bold uppercase mb-2 text-sm">{{ $banner->subtitle }}</span>
                                @endif
                                @if($banner->title)
                                    <h3 class="text-white text-2xl font-bold mb-4">{{ $banner->title }}</h3>
                                @endif
                                @if($banner->button_text && $banner->button_url)
                                    <a href="{{ $banner->button_url }}"
                                        class="text-white font-semibold hover:text-primary-400 underline decoration-2 underline-offset-4">
                                        {{ $banner->button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- New Arrivals -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Novedades') }}</h2>
                <a href="{{ route('products.index') }}"
                    class="text-sm font-semibold text-primary-600 hover:text-primary-500">Ver Todo</a>
            </div>

            @if($newProducts->isEmpty())
                <p class="text-center text-gray-500">{{ __('No hay novedades disponibles.') }}</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($newProducts as $product)
                        <x-product.card :product="$product" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>