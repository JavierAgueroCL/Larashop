<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Carrito de Compras') }}
 </h2>
 </x-slot>

 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
 <div class="p-6 text-gray-900 ">
 @if(session('success'))
 <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
 <span class="block sm:inline">{{ session('success') }}</span>
 </div>
 @endif

 @if(session('error'))
 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
 <span class="block sm:inline">{{ session('error') }}</span>
 </div>
 @endif

 @if($cart->items->isEmpty())
 <p class="text-center text-gray-500">{{ __('Tu carrito está vacío.') }}</p>
 <div class="text-center mt-4">
 <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">{{ __('Continuar Comprando') }}</a>
 </div>
 @else
 <div class="overflow-x-auto">
 <table class="min-w-full divide-y divide-gray-200 ">
 <thead class="bg-gray-50 ">
 <tr>
 <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Producto') }}</th>
 <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Precio') }}</th>
 <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Cantidad') }}</th>
 <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total') }}</th>
 <th scope="col" class="relative px-6 py-3">
 <span class="sr-only">Delete</span>
 </th>
 </tr>
 </thead>
 <tbody class="bg-white divide-y divide-gray-200 ">
 @foreach($cart->items as $item)
 <tr>
 <td class="px-6 py-4 whitespace-nowrap">
 <div class="flex items-center">
 <div class="flex-shrink-0 h-10 w-10">
 <img class="h-10 w-10 rounded-full object-cover" src="{{ $item->product->primary_image }}" alt="">
 </div>
 <div class="ml-4">
 <div class="text-sm font-medium text-gray-900 ">
 <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
 </div>
 @if($item->combination)
 <div class="text-sm text-gray-500">
 {{-- Display variant info here --}}
 </div>
 @endif
 </div>
 </div>
 </td>
 <td class="px-6 py-4 whitespace-nowrap">
 <div class="text-sm text-gray-900 ">$ {{ number_format($item->unit_price, 0, ',', '.') }}</div>
 </td>
 <td class="px-6 py-4 whitespace-nowrap">
 <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
 @csrf
 @method('PATCH')
 <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 rounded-md border-gray-300 shadow-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
 <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm">{{ __('Actualizar') }}</button>
 </form>
 </td>
 <td class="px-6 py-4 whitespace-nowrap">
 <div class="text-sm text-gray-900 ">$ {{ number_format($item->total, 0, ',', '.') }}</div>
 </td>
 <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
 <form action="{{ route('cart.remove', $item->id) }}" method="POST">
 @csrf
 @method('DELETE')
 <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Eliminar') }}</button>
 </form>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>

 <div class="mt-8 flex flex-col md:flex-row justify-between items-start gap-8">
 <!-- Coupon Code -->
 <div class="w-full md:w-1/2">
 <form action="{{ route('cart.coupon') }}" method="POST" class="flex gap-2">
 @csrf
 <input type="text" name="coupon_code" value="{{ $cart->coupon->code ?? '' }}" placeholder="{{ __('Código de Cupón') }}" class="flex-1 rounded-md border-gray-300 shadow-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
 <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 text-sm font-semibold">
 {{ __('Aplicar') }}
 </button>
 </form>
 </div>

 <!-- Totals -->
 <div class="w-full md:w-1/3 bg-gray-50 p-6 rounded-lg">
 <div class="space-y-3">
 <div class="flex justify-between items-center text-sm text-gray-600 ">
 <span>{{ __('Subtotal') }}</span>
 <span>$ {{ number_format($totals['subtotal'], 0, ',', '.') }}</span>
 </div>
 <div class="flex justify-between items-center text-sm text-gray-600 ">
 <span>{{ __('Impuestos') }}</span>
 <span>$ {{ number_format($totals['tax'], 0, ',', '.') }}</span>
 </div>
 @if($totals['discount'] > 0)
 <div class="flex justify-between items-center text-sm text-green-600 font-medium">
 <span>{{ __('Descuento') }}</span>
 <span>-$ {{ number_format($totals['discount'], 0, ',', '.') }}</span>
 </div>
 @endif
 <div class="flex justify-between items-center border-t border-gray-200 pt-4">
 <span class="text-lg font-bold text-gray-900 ">{{ __('Total') }}</span>
 <span class="text-lg font-bold text-indigo-600">$ {{ number_format($totals['total'], 0, ',', '.') }}</span>
 </div>
 </div>
 <div class="mt-6">
 <a href="{{ route('checkout.index') }}" class="block text-center w-full bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition duration-300 shadow-md">
 {{ __('Proceder al Pago') }}
 </a>
 </div>
 </div>
 </div>
 @endif
 </div>
 </div>
 </div>
 </div>
</x-app-layout>
