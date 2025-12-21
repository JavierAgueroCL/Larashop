<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Help & Contact') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4">
                    <x-dashboard-sidebar />
                </div>

                <!-- Main Content -->
                <div class="w-full md:w-3/4">
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-6 border border-gray-300">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __("Need Help?") }}</h3>
                            <p class="mb-4 text-gray-600">{{ __("If you have any questions or need assistance with your order, please don't hesitate to contact us.") }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-300 shadow-sm">
                                    <h4 class="font-bold mb-2 flex items-center text-gray-800">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ __("Email Support") }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ __("Send us an email and we'll get back to you as soon as possible.") }}
                                    </p>
                                    <a href="mailto:support@larashop.test" class="text-indigo-600 hover:underline font-medium">support@larashop.test</a>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-300 shadow-sm">
                                    <h4 class="font-bold mb-2 flex items-center text-gray-800">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ __("Phone Support") }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ __("Call us directly for urgent matters.") }}
                                    </p>
                                    <a href="tel:+1234567890" class="text-indigo-600 hover:underline font-medium">+1 (234) 567-890</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-300">
                        <div class="p-6 text-gray-900">
                             <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __("Send us a Message") }}</h3>

                             @if(session('success'))
                                <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                             @endif
                             
                             <form action="{{ route('dashboard.help.submit') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Subject') }}</label>
                                        <select name="subject" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option>{{ __('Order Inquiry') }}</option>
                                            <option>{{ __('Technical Support') }}</option>
                                            <option>{{ __('Product Question') }}</option>
                                            <option>{{ __('Other') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Message') }}</label>
                                        <textarea name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('How can we help you?') }}"></textarea>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium shadow-md transition-colors">
                                            {{ __('Send Message') }}
                                        </button>
                                    </div>
                                </div>
                             </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>