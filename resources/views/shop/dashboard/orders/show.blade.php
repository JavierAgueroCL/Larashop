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
                                    {{ __('shop.statuses.' . $order->current_status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <!-- Columna Izquierda: Facturación y Pago -->
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="font-bold text-gray-800 border-b pb-2 mb-3">{{ __("Datos de Facturación") }}</h4>
                                        @if($order->billingAddress)
                                            <p class="font-medium text-gray-900">{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</p>
                                            @if($order->billingAddress->company)
                                                <p class="text-gray-600">{{ $order->billingAddress->company }}</p>
                                            @endif
                                            @if($order->billingAddress->rut)
                                                <p class="text-gray-600 text-sm">RUT: {{ $order->billingAddress->rut }}</p>
                                            @endif
                                            <p class="text-gray-600">{{ $order->billingAddress->address_line_1 }}</p>
                                            @if($order->billingAddress->address_line_2)
                                                <p class="text-gray-600">{{ $order->billingAddress->address_line_2 }}</p>
                                            @endif
                                            <p class="text-gray-600">
                                                {{ $order->billingAddress->comuna->comuna ?? '' }}, {{ $order->billingAddress->region->region ?? '' }}
                                            </p>
                                            <p class="text-gray-600">{{ $order->billingAddress->phone }}</p>
                                        @else
                                            <p class="text-gray-500 italic">{{ __("Dirección no disponible") }}</p>
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-gray-800 border-b pb-2 mb-3">{{ __("Método de Pago") }}</h4>
                                        <p class="text-gray-700">
                                            @switch($order->payment_method)
                                                @case('transbank')
                                                    {{ __("Webpay Plus (Débito/Crédito)") }}
                                                    @break
                                                @case('bank_transfer')
                                                    {{ __("Transferencia Bancaria") }}
                                                    @break
                                                @case('paypal')
                                                    {{ __("PayPal") }}
                                                    @break
                                                @default
                                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                            @endswitch
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Estado: <span class="font-semibold">{{ __('shop.payment_statuses.' . $order->payment_status) }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Columna Derecha: Envío -->
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="font-bold text-gray-800 border-b pb-2 mb-3">{{ __("Datos de Envío") }}</h4>
                                        @if($order->shippingAddress)
                                            <p class="font-medium text-gray-900">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                                            <p class="text-gray-600">{{ $order->shippingAddress->address_line_1 }}</p>
                                            @if($order->shippingAddress->address_line_2)
                                                <p class="text-gray-600">{{ $order->shippingAddress->address_line_2 }}</p>
                                            @endif
                                            <p class="text-gray-600">
                                                {{ $order->shippingAddress->comuna->comuna ?? '' }}, {{ $order->shippingAddress->region->region ?? '' }}
                                            </p>
                                            <p class="text-gray-600">{{ $order->shippingAddress->phone }}</p>
                                        @else
                                            <p class="text-gray-500 italic">{{ __("Dirección no disponible") }}</p>
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-gray-800 border-b pb-2 mb-3">{{ __("Método de Envío") }}</h4>
                                        <p class="text-gray-700">{{ $order->shipping_method }}</p>
                                        @if($order->tracking_number)
                                            <div class="mt-2 p-3 bg-gray-50 rounded border border-gray-200">
                                                <p class="text-sm font-semibold text-gray-700">{{ __("Número de Seguimiento:") }}</p>
                                                <p class="text-indigo-600 font-mono">{{ $order->tracking_number }}</p>
                                            </div>
                                        @endif
                                    </div>
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
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium text-gray-600">{{ __("Impuestos") }}</span>
                                    <span class="text-gray-900">$ {{ number_format($order->tax_total, 0, ',', '.') }}</span>
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