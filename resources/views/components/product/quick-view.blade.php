<div class="relative bg-white rounded-lg max-w-2xl w-full p-6 shadow-xl">
    <button @click="open = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 ">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
 </svg>
 </button>
 
 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
 <div class="h-64 md:h-full bg-gray-200 rounded-lg overflow-hidden">
 @if($product->primary_image)
 <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
 @else
 <div class="flex items-center justify-center h-full text-gray-400">No Image</div>
 @endif
 </div>
 
 <div>
 <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
 <p class="text-xl text-indigo-600 font-bold mb-4">{{ $product->base_price_formatted }}</p>
 
 <div class="prose text-sm mb-6">
 {{ Str::limit($product->short_description, 150) }}
 </div>
 
 <a href="{{ route('products.show', $product->slug) }}" class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-medium transition duration-150">
 {{ __('View Full Details') }}
 </a>
 </div>
 </div>
</div>