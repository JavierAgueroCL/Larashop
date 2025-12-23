<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Billing Address') }}
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
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-300">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-6 text-gray-800">{{ __("My Billing Address") }}</h3>

                            @if(session('success'))
                                <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif
                            
                            @if(!$address)
                                <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                    <p class="text-gray-500 mb-4">{{ __("You haven't set a billing address yet.") }}</p>
                                    <a href="{{ route('addresses.create', ['type' => 'billing']) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors shadow-md">
                                        {{ __('Add Billing Address') }}
                                    </a>
                                </div>
                            @else
                                <div class="border border-gray-300 rounded-lg p-6 relative max-w-lg bg-white shadow-sm">
                                    <div class="absolute top-4 right-4 flex space-x-2">
                                        <a href="{{ route('addresses.edit', $address) }}" class="bg-white text-indigo-600 border border-indigo-300 hover:bg-indigo-50 px-3 py-1 rounded-md text-sm font-medium transition-colors">Edit</a>
                                        <form action="{{ route('addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete your billing address?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-white text-red-600 border border-red-300 hover:bg-red-50 px-3 py-1 rounded-md text-sm font-medium transition-colors">Delete</button>
                                        </form>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            Billing Address
                                        </span>
                                    </div>

                                    <p class="font-bold text-lg text-gray-900">{{ $address->first_name }} {{ $address->last_name }}</p>
                                    @if($address->company)
                                        <p class="text-sm text-gray-600">{{ $address->company }}</p>
                                    @endif
                                    <div class="mt-2 text-gray-700">
                                        <p>{{ $address->address_line_1 }}</p>
                                        @if($address->address_line_2)
                                            <p>{{ $address->address_line_2 }}</p>
                                        @endif
                                        <p>{{ $address->city }}, {{ $address->state_province }}</p>
                                        <p>{{ $address->country_code }}</p>
                                    </div>
                                    <p class="mt-4 text-sm text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $address->phone }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
