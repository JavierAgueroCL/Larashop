<nav class="bg-white border-b border-gray-200 hidden md:block">
    <div class="max-w-[1350px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-8 h-12">
 
            <!-- Browse Categories Button -->
            <div class="relative group z-50">
                <button class="flex items-center text-white bg-primary-500 px-4 h-12 font-semibold uppercase text-sm tracking-wide transition-colors hover:bg-primary-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    Explorar Categorías
                </button>
                
                <!-- Mega Menu Dropdown -->
                <div class="absolute left-0 top-full w-64 bg-white shadow-xl border border-gray-100 hidden group-hover:block">
                    <ul class="py-2">
                        @foreach($globalCategories as $category)
                            <li class="group/item relative">
                                <a href="{{ route('products.category', $category->slug) }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:text-primary-600 hover:bg-gray-50 transition-colors">
                                    <span class="flex-1 truncate">{{ $category->name }}</span>
                                    @if($category->children->isNotEmpty())
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    @endif
                                </a>

                                @if($category->children->isNotEmpty())
                                    <!-- Submenu Flyout -->
                                    <div class="absolute left-full top-0 w-[600px] min-h-full bg-white shadow-xl border border-gray-100 hidden group-hover/item:block p-6 -ml-1">
                                        <div class="grid grid-cols-3 gap-6">
                                            @foreach($category->children as $child)
                                                <div>
                                                    <a href="{{ route('products.category', $child->slug) }}" class="font-bold text-gray-800 hover:text-primary-600 mb-2 block text-sm">
                                                        {{ $child->name }}
                                                    </a>
                                                    @if($child->children->isNotEmpty())
                                                        <ul class="space-y-1">
                                                            @foreach($child->children as $grandchild)
                                                                <li>
                                                                    <a href="{{ route('products.category', $grandchild->slug) }}" class="text-xs text-gray-500 hover:text-primary-500 transition-colors block py-0.5">
                                                                        {{ $grandchild->name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

             <!-- Main Menu -->
             <div class="flex space-x-8">
                 @php $headerMenu = get_menu('header'); @endphp
                 @if($headerMenu)
                     @foreach($headerMenu->items as $item)
                         <a href="{{ $item->link }}" class="text-sm font-medium {{ request()->url() == $item->link ? 'text-primary-500' : 'text-gray-700 hover:text-primary-500' }} uppercase tracking-wide" target="{{ $item->target }}">
                             {{ $item->title }}
                         </a>
                     @endforeach
                 @endif
             </div>
 </div>
 </div>
</nav>
