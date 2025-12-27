<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Direcciones de Envío') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-[1350px] mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4">
                    <x-dashboard-sidebar />
                </div>

                <!-- Main Content -->
                <div class="w-full md:w-3/4">
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-300">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-800">{{ __("Mis direcciones de envío") }}</h3>
                                <a href="{{ route('addresses.create', ['type' => 'shipping']) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-md">
                                    {{ __('Añadir nueva dirección') }}
                                </a>
                            </div>

                            @if(session('success'))
                                <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif
                            
                            @if($addresses->isEmpty())
                                <p class="text-gray-500 italic border border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">{{ __("Aún no has guardado ninguna dirección de envío.") }}</p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($addresses as $address)
                                        <div class="border border-gray-300 bg-white rounded-lg p-4 relative hover:shadow-lg transition-all group">
                                            <div class="absolute top-4 right-4 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('addresses.edit', $address) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">{{ __('Editar') }}</a>
                                                <form action="{{ route('addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('{{ __('¿Estás seguro de que quieres eliminar esta dirección?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium ml-2">{{ __('Eliminar') }}</button>
                                                </form>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <span class="font-bold text-lg text-gray-800">{{ $address->alias ?? __('Dirección') }}</span>
                                                @if($address->is_default)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-300">
                                                        {{ __('Predeterminada') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="font-semibold text-gray-900">{{ $address->first_name }} {{ $address->last_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $address->address_line_1 }}</p>
                                            @if($address->address_line_2)
                                                <p class="text-sm text-gray-600">{{ $address->address_line_2 }}</p>
                                            @endif
                                            <p class="text-sm text-gray-600">{{ $address->comuna->comuna ?? '' }}, {{ $address->region->region ?? '' }}</p>
                                            <p class="text-sm text-gray-600">{{ $address->country_code }}</p>
                                            <p class="text-sm text-gray-600 mt-2 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                {{ $address->phone }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>