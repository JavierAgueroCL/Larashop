<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ $page->title }}
 </h2>
 </x-slot>

 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                 <div class="p-8 text-gray-900 prose max-w-none">
                     {!! $page->content !!}
 
                     @if($page->slug === 'contact')
                         <div class="mt-12 border-t border-gray-200 pt-8 not-prose">
                             <h3 class="text-2xl font-bold mb-6">{{ __('Envíenos un Mensaje') }}</h3>
 
                             @if(session('success'))
                                 <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded relative mb-4" role="alert">
                                     <span class="block sm:inline">{{ session('success') }}</span>
                                 </div>
                             @endif
 
                             <form action="{{ route('dashboard.help.submit') }}" method="POST" class="max-w-2xl">
                                 @csrf
                                 <div class="grid grid-cols-1 gap-6">
                                     <div>
                                         <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Asunto') }}</label>
                                         <select name="subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                             <option>{{ __('Consulta General') }}</option>
                                             <option>{{ __('Soporte de Pedidos') }}</option>
                                             <option>{{ __('Consulta de Negocios') }}</option>
                                             <option>{{ __('Otro') }}</option>
                                         </select>
                                     </div>
                                     
                                     <div>
                                         <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mensaje') }}</label>
                                         <textarea name="message" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('¿En qué podemos ayudarle?') }}"></textarea>
                                     </div>
 
                                     <div>
                                         <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-medium shadow-md transition-colors">
                                             {{ __('Enviar Mensaje') }}
                                         </button>
                                     </div>
                                 </div>
                             </form>
                         </div>
                     @endif
                 </div> </div>
 </div>
 </div>
</x-app-layout>