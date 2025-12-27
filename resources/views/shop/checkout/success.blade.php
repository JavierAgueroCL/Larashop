<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Pedido Confirmado') }}
 </h2>
 </x-slot>

 <div class="py-12">
 <div class="max-w-[1350px] mx-auto sm:px-6 lg:px-8 text-center">
 <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-12">
 <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-500 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
 </svg>
 
 <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('¡Gracias por tu pedido!') }}</h1>
             <p class="text-lg text-gray-600 mb-8">
                 {{ __('Tu pedido') }} <span class="font-bold">#{{ $order->order_number }}</span> {{ __('ha sido realizado con éxito.') }}
             </p>
 
             @if($order->payment_method === 'bank_transfer')
                 <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8 text-left max-w-md mx-auto">
                     <h3 class="font-bold text-lg mb-4 text-indigo-700">{{ __('Datos de Transferencia') }}</h3>
                     <p class="text-sm text-gray-600 mb-4">{{ __('Por favor, realiza la transferencia a la siguiente cuenta y envía el comprobante a nuestro correo.') }}</p>
                     
                     <ul class="text-sm space-y-2 text-gray-800">
                         <li><strong>{{ __('Banco:') }}</strong> {{ config('payment.gateways.bank_transfer.details.bank_name') }}</li>
                         <li><strong>{{ __('Tipo de Cuenta:') }}</strong> {{ config('payment.gateways.bank_transfer.details.account_type') }}</li>
                         <li><strong>{{ __('Número de Cuenta:') }}</strong> {{ config('payment.gateways.bank_transfer.details.account_number') }}</li>
                         <li><strong>{{ __('Titular:') }}</strong> {{ config('payment.gateways.bank_transfer.details.account_holder') }}</li>
                         <li><strong>{{ __('RUT:') }}</strong> {{ config('payment.gateways.bank_transfer.details.rut') }}</li>
                         <li><strong>{{ __('Correo:') }}</strong> {{ config('payment.gateways.bank_transfer.details.email') }}</li>
                     </ul>
                     
                     <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-sm">
                         <p>{{ __('IMPORTANTE: Indica tu número de pedido (#:order) en el asunto del correo.', ['order' => $order->order_number]) }}</p>
                     </div>
                 </div>
             @endif
             
             <div class="flex justify-center gap-4"> <a href="{{ route('home') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md hover:bg-gray-300 ">
 {{ __('Volver al Inicio') }}
 </a>
 <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">
 {{ __('Ver Pedido') }}
 </a>
 </div>
 </div>
 </div>
 </div>
</x-app-layout>
