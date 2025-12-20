<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Confirmed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-500 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Thank you for your order!') }}</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                    {{ __('Your order') }} <span class="font-bold">#{{ $order->order_number }}</span> {{ __('has been placed successfully.') }}
                </p>
                
                <div class="flex justify-center gap-4">
                    <a href="{{ route('home') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-6 py-3 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        {{ __('Return Home') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">
                        {{ __('View Order') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
