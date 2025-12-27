@props(['category', 'level' => 0])

@php
    // Determine if this category or any of its descendants is currently active
    $isActive = request('category') == $category->slug;
    $hasActiveChild = false;
    
    // Simple check for direct children, for deeper recursion we rely on the parent keeping state open?
    // Ideally we would check recursively but for performance in View simple check is better.
    // If we want the tree to be open to the active item, we need to know if a descendant is active.
    
    // Let's rely on Alpine init logic if possible, or simple blade check.
    // For now, let's open if direct child is active.
    if ($category->children->isNotEmpty()) {
        foreach($category->children as $child) {
            if (request('category') == $child->slug) {
                $hasActiveChild = true;
                break;
            }
            // Check grandchildren
            if ($child->children->isNotEmpty()) {
                 foreach($child->children as $grandchild) {
                    if (request('category') == $grandchild->slug) {
                        $hasActiveChild = true;
                        break 2;
                    }
                 }
            }
        }
    }
@endphp

<li x-data="{ open: {{ $isActive || $hasActiveChild ? 'true' : 'false' }} }" class="{{ $level > 0 ? 'ml-3 border-l border-gray-200' : '' }}">
    <div class="flex items-center justify-between group hover:bg-gray-50 rounded-r-md transition-colors duration-150"
         :class="{ 'bg-gray-50': open && {{ $level > 0 ? 'true' : 'false' }} }"
    >
        <a href="{{ route('products.category', $category->slug) }}"
           class="{{ $isActive ? 'text-indigo-600 font-bold' : 'text-gray-700 hover:text-indigo-600' }} flex-1 py-2 text-sm block"
           style="padding-left: {{ 12 + ($level * 4) }}px"
        >
            {{ $category->name }}
            @if(config('shop.show_product_count'))
                 <span class="text-xs {{ $isActive ? 'text-indigo-400' : 'text-gray-400' }} ml-1">({{ $category->children->isNotEmpty() ? $category->children->sum('products_count') : $category->products_count }})</span>
            @endif
        </a>
        
        @if($category->children->isNotEmpty())
            <button @click="open = !open" class="p-2 text-gray-400 hover:text-indigo-500 transition-colors focus:outline-none">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <svg x-show="open" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform duration-200 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        @endif
    </div>

    @if($category->children->isNotEmpty())
        <ul x-show="open" class="space-y-1 overflow-hidden transition-all duration-300">
            @foreach($category->children as $child)
                <x-shop.category-item :category="$child" :level="$level + 1" />
            @endforeach
        </ul>
    @endif
</li>
