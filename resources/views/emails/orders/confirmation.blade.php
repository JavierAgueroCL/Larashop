<x-mail::message>
# {{ __('Confirmación de Pedido') }}

{{ __('¡Gracias por tu pedido, :name!', ['name' => $order->customer_first_name]) }}

{{ __('Tu pedido #:number ha sido recibido exitosamente.', ['number' => $order->order_number]) }}

<x-mail::table>
| {{ __('Producto') }} | {{ __('Cant') }} | {{ __('Precio') }} |
| :--- | :---: | :---: |
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | $ {{ number_format($item->unit_price, 0, ',', '.') }} |
@endforeach
</x-mail::table>

<x-mail::panel>
**{{ __('Resumen del Pedido') }}**

| | |
| :--- | ---: |
| {{ __('Subtotal') }} | $ {{ number_format($order->subtotal, 0, ',', '.') }} |
| {{ __('Impuestos') }} | $ {{ number_format($order->tax_total, 0, ',', '.') }} |
| {{ __('Envío') }} | $ {{ number_format($order->shipping_cost, 0, ',', '.') }} |
| **{{ __('Total') }}** | **$ {{ number_format($order->grand_total, 0, ',', '.') }}** |
</x-mail::panel>

@if($order->payment_method === 'bank_transfer')
## {{ __('Instrucciones de Pago') }}

{{ __('Por favor, realiza la transferencia bancaria a la siguiente cuenta:') }}

*   **{{ __('Banco') }}:** {{ config('payment.gateways.bank_transfer.details.bank_name') }}
*   **{{ __('Tipo de Cuenta') }}:** {{ config('payment.gateways.bank_transfer.details.account_type') }}
*   **{{ __('Número de Cuenta') }}:** {{ config('payment.gateways.bank_transfer.details.account_number') }}
*   **{{ __('Titular') }}:** {{ config('payment.gateways.bank_transfer.details.account_holder') }}
*   **{{ __('RUT') }}:** {{ config('payment.gateways.bank_transfer.details.rut') }}
*   **{{ __('Email') }}:** {{ config('payment.gateways.bank_transfer.details.email') }}

{{ __('Por favor, envía el comprobante de transferencia indicando tu número de pedido #:number.', ['number' => $order->order_number]) }}
@endif

<x-mail::button :url="route('orders.show', $order)">
{{ __('Ver Pedido') }}
</x-mail::button>

{{ __('Gracias,') }}<br>
{{ config('app.name') }}
</x-mail::message>