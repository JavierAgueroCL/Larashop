<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Pedido') }} #{{ $order->order_number }}
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
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-800">{{ __("Información del Pedido") }}</h3>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                    {{ strtoupper($order->current_status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-2">{{ __("Cliente") }}</h4>
                                    <p>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</p>
                                    <p>{{ $order->customer_email }}</p>
                                    <p>{{ $order->customer_phone }}</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-2">{{ __("Envío") }}</h4>
                                    <p>{{ $order->shipping_method }}</p>
                                    @if($order->tracking_number)
                                        <p>{{ __("Seguimiento:") }} {{ $order->tracking_number }}</p>
                                    @endif
                                </div>
                            </div>

                            <h4 class="font-semibold text-gray-700 mb-4">{{ __("Artículos") }}</h4>
                            <div class="overflow-x-auto mb-8">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Producto") }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Precio") }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Cantidad") }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Total") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->product_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">$ {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->quantity }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">$ {{ number_format($item->total, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium text-gray-600">{{ __("Subtotal") }}</span>
                                    <span class="text-gray-900">$ {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium text-gray-600">{{ __("Envío") }}</span>
                                    <span class="text-gray-900">$ {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold mt-4">
                                    <span class="text-gray-800">{{ __("Total") }}</span>
                                    <span class="text-indigo-600">$ {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-8 text-right">
                                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:underline">{{ __("Volver a mis pedidos") }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>