<x-mail::message>
# {{ __('Order Status Updated') }}

{{ __('Hello :name,', ['name' => $order->customer_first_name]) }}

{{ __('The status of your order # :number has been updated to:', ['number' => $order->order_number]) }}

<x-mail::panel>
**{{ strtoupper($order->current_status) }}**
</x-mail::panel>

<x-mail::button :url="route('dashboard')">
{{ __('View Order Details') }}
</x-mail::button>

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>