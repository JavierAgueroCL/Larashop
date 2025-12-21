<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Addresses') }}
 </h2>
 </x-slot>

 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 <div class="flex flex-col md:flex-row gap-8">
 <!-- Sidebar (Izquierda) -->
 <div class="w-full md:w-1/4">
 <x-dashboard-sidebar />
 </div>

 <!-- Main Content (Derecha) -->
 <div class="w-full md:w-3/4">
 <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
 <div class="p-6 text-gray-900 ">
 <div class="flex justify-between items-center mb-6">
 <h3 class="text-lg font-bold">{{ __("Your Addresses") }}</h3>
 <a href="{{ route('addresses.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
 {{ __('Add New Address') }}
 </a>
 </div>

 @if(session('success'))
 <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
 <span class="block sm:inline">{{ session('success') }}</span>
 </div>
 @endif
 
 @if($addresses->isEmpty())
 <p class="text-gray-500 italic">{{ __("You haven't saved any addresses yet.") }}</p>
 @else
 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
 @foreach($addresses as $address)
 <div class="border border-gray-200 rounded-lg p-4 relative hover:shadow-md transition-shadow">
 <div class="absolute top-4 right-4 flex space-x-2">
 <a href="{{ route('addresses.edit', $address) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
 <form action="{{ route('addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this address?') }}');">
 @csrf
 @method('DELETE')
 <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
 </form>
 </div>
 
 <div class="mb-2">
 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $address->address_type === 'shipping' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
 {{ ucfirst($address->address_type) }}
 </span>
 @if($address->is_default)
 <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ">
 Default
 </span>
 @endif
 </div>

 <p class="font-bold">{{ $address->first_name }} {{ $address->last_name }}</p>
 @if($address->company)
 <p class="text-sm text-gray-600 ">{{ $address->company }}</p>
 @endif
 <p class="text-sm text-gray-600 ">{{ $address->address_line_1 }}</p>
 @if($address->address_line_2)
 <p class="text-sm text-gray-600 ">{{ $address->address_line_2 }}</p>
 @endif
 <p class="text-sm text-gray-600 ">{{ $address->city }}, {{ $address->state_province }} {{ $address->postal_code }}</p>
 <p class="text-sm text-gray-600 ">{{ $address->country_code }}</p>
 <p class="text-sm text-gray-600 mt-2 font-medium">{{ $address->phone }}</p>
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