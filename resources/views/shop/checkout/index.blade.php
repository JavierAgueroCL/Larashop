<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="flex flex-col md:flex-row gap-8">
                    
                    <!-- Left Column: Address & Payment -->
                    <div class="w-full md:w-2/3 space-y-6">
                        
                        <!-- Shipping Address -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Shipping Address') }}</h3>
                            
                            @guest
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email Address') }}</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                            @endguest

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('First Name') }}</label>
                                    <input type="text" name="shipping_address[first_name]" value="{{ old('shipping_address.first_name', $user->first_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Last Name') }}</label>
                                    <input type="text" name="shipping_address[last_name]" value="{{ old('shipping_address.last_name', $user->last_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Address') }}</label>
                                    <input type="text" name="shipping_address[address_line_1]" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('City') }}</label>
                                    <input type="text" name="shipping_address[city]" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Postal Code') }}</label>
                                    <input type="text" name="shipping_address[postal_code]" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Country') }}</label>
                                    <select name="shipping_address[country_code]" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="ES">Spain</option>
                                        <option value="US">United States</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
                                    <input type="text" name="shipping_address[phone]" value="{{ old('shipping_address.phone', $user->phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                <input type="hidden" name="shipping_address[state_province]" value="NA"> 
                            </div>

                            @guest
                                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4" x-data="{ createAccount: false }">
                                    <div class="flex items-center">
                                        <input id="create_account" name="create_account" type="checkbox" value="1" x-model="createAccount" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="create_account" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                            {{ __('Create an account?') }}
                                        </label>
                                    </div>
                                    
                                    <div class="mt-4 space-y-4" x-show="createAccount" x-cloak>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Password') }}</label>
                                            <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Confirm Password') }}</label>
                                            <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                    </div>
                                </div>
                            @endguest
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Payment Method') }}</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="bank_transfer" name="payment_method" type="radio" value="bank_transfer" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="bank_transfer" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Bank Transfer') }}
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="paypal" name="payment_method" type="radio" value="paypal" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="paypal" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('PayPal') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="w-full md:w-1/3">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm sticky top-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Order Summary') }}</h3>
                            
                            <div class="space-y-4 mb-6">
                                @foreach($cart->items as $item)
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->quantity }}x {{ $item->product->name }}</div>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($item->total, 2) }} €</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-600 pt-4 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span>{{ __('Subtotal') }}</span>
                                    <span>{{ number_format($totals['subtotal'], 2) }} €</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>{{ __('Tax') }}</span>
                                    <span>{{ number_format($totals['tax'], 2) }} €</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold">
                                    <span>{{ __('Total') }}</span>
                                    <span>{{ number_format($totals['total'], 2) }} €</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-6 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-bold">
                                {{ __('Place Order') }}
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
