<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                        <p class="text-center text-gray-500">{{ __('Your cart is empty.') }}</p>
                        <div class="text-center mt-4">
                            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">{{ __('Continue Shopping') }}</a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Product') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Price') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Total') }}</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Delete</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($cart->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $item->product->primary_image }}" alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
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
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->unit_price, 2) }} €</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm">{{ __('Update') }}</button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($item->total, 2) }} €</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Remove') }}</button>
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
                                    <input type="text" name="coupon_code" value="{{ $cart->coupon->code ?? '' }}" placeholder="{{ __('Coupon Code') }}" class="flex-1 rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <button type="submit" class="bg-gray-800 dark:bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-300 text-sm font-semibold">
                                        {{ __('Apply') }}
                                    </button>
                                </form>
                            </div>

                            <!-- Totals -->
                            <div class="w-full md:w-1/3 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                                        <span>{{ __('Subtotal') }}</span>
                                        <span>{{ number_format($totals['subtotal'], 2) }} €</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                                        <span>{{ __('Tax') }}</span>
                                        <span>{{ number_format($totals['tax'], 2) }} €</span>
                                    </div>
                                    @if($totals['discount'] > 0)
                                        <div class="flex justify-between items-center text-sm text-green-600 font-medium">
                                            <span>{{ __('Discount') }}</span>
                                            <span>-{{ number_format($totals['discount'], 2) }} €</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-600 pt-4">
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Total') }}</span>
                                        <span class="text-lg font-bold text-indigo-600">{{ number_format($totals['total'], 2) }} €</span>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <a href="{{ route('checkout.index') }}" class="block text-center w-full bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition duration-300 shadow-md">
                                        {{ __('Proceed to Checkout') }}
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
