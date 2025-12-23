<x-mail::message>
# {{ __('Order Confirmation') }}

{{ __('Thank you for your order, :name!', ['name' => $order->customer_first_name]) }}

{{ __('Your order # :number has been successfully placed.', ['number' => $order->order_number]) }}

<x-mail::table>
| {{ __('Product') }} | {{ __('Qty') }} | {{ __('Price') }} |
| :--- | :---: | :---: |
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ number_format($item->unit_price, 0, ',', '.') }} $ |
@endforeach
</x-mail::table>

<x-mail::panel>
**{{ __('Total') }}: {{ number_format($order->grand_total, 0, ',', '.') }} $**
</x-mail::panel>

<x-mail::button :url="route('dashboard')">
{{ __('View Order') }}
</x-mail::button>

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>