<div x-data="{ open: false, html: '' }" @open-cart.window="open = true; html = $event.detail.html" x-show="open"
    class="fixed inset-0 overflow-hidden z-50" style="display: none;" x-cloak>

    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false" x-show="open"
            x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex h-full">
            <div class="w-screen max-w-md" x-show="open"
                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="h-full flex flex-col bg-white shadow-xl">
                    <!-- Header -->
                    <div class="px-4 sm:px-6 py-6 border-b border-gray-200">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-medium text-gray-900">{{ __('Shopping Cart') }}</h2>
                            <div class="ml-3 h-7 flex items-center">
                                <button type="button" @click="open = false"
                                    class="-m-2 p-2 text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Content -->
                    <div x-html="html" class="flex-1 overflow-y-auto">
                        <div class="flex h-full items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>