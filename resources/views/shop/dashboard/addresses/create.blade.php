<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Añadir Nueva Dirección') }}
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
 <h3 class="text-lg font-bold mb-6 text-gray-800">
 {{ $type === 'billing' ? __('Crear Dirección de Facturación') : __('Crear Dirección de Envío') }}
 </h3>

 <form action="{{ route('addresses.store') }}" method="POST" 
       x-data="{ 
           type: '{{ old('address_type', $type) }}',
           regions: {{ $regions->toJson() }},
           selectedRegion: '{{ old('region_id') }}',
           selectedComuna: '{{ old('comuna_id') }}',
           document_type: '{{ old('document_type', 'boleta') }}',
           comunas: [],
           
           init() {
               if(this.selectedRegion) {
                   this.fetchComunas(this.selectedRegion);
               }
               
               this.$watch('selectedRegion', value => {
                   this.comunas = [];
                   if(value) {
                       this.fetchComunas(value);
                       if (value != '{{ old('region_id') }}') {
                           this.selectedComuna = '';
                       }
                   } else {
                       this.selectedComuna = '';
                   }
               });
           },
           
           fetchComunas(regionId) {
               fetch(`/locations/regions/${regionId}/comunas`)
                   .then(res => res.json())
                   .then(data => {
                       this.comunas = data;
                   });
           }
       }">
 @csrf
 
 <input type="hidden" name="address_type" x-model="type">

 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
 
 <!-- Alias (Only for Shipping) -->
 <div class="col-span-2" x-show="type === 'shipping'">
 <label class="block text-sm font-medium text-gray-700">{{ __('Alias de la Dirección') }} <span class="text-gray-500 text-xs">({{ __('ej., Casa, Trabajo') }})</span></label>
 <input type="text" name="alias" value="{{ old('alias') }}" placeholder="{{ __('Mi Casa') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
 @error('alias') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <!-- Document Type -->
 <div class="col-span-2" x-show="type !== 'shipping'">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Tipo de Documento') }}</label>
    <div class="flex items-center gap-4">
        <label class="flex items-center">
            <input type="radio" name="document_type" value="boleta" x-model="document_type" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
            <span class="ml-2 text-sm text-gray-700">{{ __('Boleta') }}</span>
        </label>
        <label class="flex items-center">
            <input type="radio" name="document_type" value="factura" x-model="document_type" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
            <span class="ml-2 text-sm text-gray-700">{{ __('Factura') }}</span>
        </label>
    </div>
 </div>

 <!-- Personal Info -->
 <div>
 <label class="block text-sm font-medium text-gray-700">{{ __('Nombre') }}</label>
 <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
 @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700">{{ __('Apellido') }}</label>
 <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
 @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <!-- RUT -->
 <div x-show="type !== 'shipping'">
    <label class="block text-sm font-medium text-gray-700">{{ __('RUT') }}</label>
    <input type="text" name="rut" value="{{ old('rut') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
    @error('rut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <!-- Company (Conditional) -->
 <div x-show="type !== 'shipping' && document_type === 'factura'">
    <label class="block text-sm font-medium text-gray-700">{{ __('Razón Social') }}</label>
    <input type="text" name="company" value="{{ old('company') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
    @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <!-- Giro (Conditional) -->
 <div class="col-span-2" x-show="type !== 'shipping' && document_type === 'factura'">
    <label class="block text-sm font-medium text-gray-700">{{ __('Giro') }}</label>
    <input type="text" name="business_activity" value="{{ old('business_activity') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
    @error('business_activity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700">{{ __('Teléfono') }}</label>
 <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
 @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <!-- Address Details -->
 <div class="col-span-2">
 <label class="block text-sm font-medium text-gray-700">{{ __('Dirección Línea 1') }}</label>
 <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
 @error('address_line_1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <div class="col-span-2">
 <label class="block text-sm font-medium text-gray-700">{{ __('Dirección Línea 2 (Opcional)') }}</label>
 <input type="text" name="address_line_2" value="{{ old('address_line_2') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
 </div>

 <div>
    <label class="block text-sm font-medium text-gray-700">{{ __('Región') }}</label>
    <select name="region_id" x-model="selectedRegion" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">{{ __('Seleccione una región') }}</option>
        <template x-for="region in regions" :key="region.id">
            <option :value="region.id" x-text="region.region"></option>
        </template>
    </select>
    @error('region_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <div>
    <label class="block text-sm font-medium text-gray-700">{{ __('Comuna') }}</label>
    <select name="comuna_id" x-model="selectedComuna" :disabled="!comunas.length" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 shadow-md focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100">
        <option value="">{{ __('Seleccione una comuna') }}</option>
        <template x-for="comuna in comunas" :key="comuna.id">
            <option :value="comuna.id" x-text="comuna.comuna"></option>
        </template>
    </select>
    @error('comuna_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700">{{ __('País') }}</label>
 <input type="text" value="Chile" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
 <input type="hidden" name="country_code" value="CL">
 </div>

 <div class="col-span-2">
 <div class="flex items-center">
 <input id="is_default" name="is_default" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
 <label for="is_default" class="ml-2 block text-sm text-gray-900">
 {{ __('Establecer como dirección predeterminada') }}
 </label>
 </div>
 </div>
 </div>

 <div class="mt-6 flex items-center justify-end gap-4 border-t border-gray-300 pt-6">
 <a href="{{ $type === 'billing' ? route('addresses.billing') : route('addresses.shipping') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">{{ __('Cancelar') }}</a>
 <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium shadow-md transition-colors">
 {{ __('Guardar Dirección') }}
 </button>
 </div>
 </form>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
</x-app-layout>