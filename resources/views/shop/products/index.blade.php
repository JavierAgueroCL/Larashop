<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Tienda') }}
 </h2>
 </x-slot>

 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 <div class="flex flex-col md:flex-row gap-8">
 
                 <!-- Sidebar Filters -->
                 <div class="w-full md:w-1/4">
                     @include('components.shop.sidebar')
                 </div>
 <!-- Product Grid -->
 <div class="w-full md:w-3/4">
 
 <!-- Sorting & count -->
 <div class="flex justify-between items-center mb-6">
 <p class="text-sm text-gray-600 ">
 Mostrando {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} de {{ $products->total() }} resultados
 </p>
 <div class="flex items-center">
 <label for="sort" class="mr-2 text-sm text-gray-600 ">{{ __('Ordenar por:') }}</label>
 <select id="sort" onchange="window.location.href=this.value" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-md text-sm">
 <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más Recientes</option>
 <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Bajo a Alto</option>
 <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Alto a Bajo</option>
 </select>
 </div>
 </div>

 @if($products->isEmpty())
 <div class="bg-white p-8 rounded-lg shadow-md text-center">
 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-gray-400 mb-4">
 <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
 </svg>
 <p class="text-gray-500 text-lg">{{ __('No se encontraron productos que coincidan con su selección.') }}</p>
 <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">{{ __('Limpiar filtros') }}</a>
 </div>
 @else
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
 @foreach($products as $product)
 <x-product.card :product="$product" />
 @endforeach
 </div>
 
 <div class="mt-6">
 {{ $products->appends(request()->query())->links() }}
 </div>
 @endif
 </div>
 </div>
 </div>
 </div>
</x-app-layout>
