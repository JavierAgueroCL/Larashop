<x-mail::message>
# ¡Bienvenido a LaraShop, {{ $user->first_name }}!

Estamos encantados de que te hayas unido a nuestra comunidad. En LaraShop encontrarás los mejores productos a los mejores precios.

<x-mail::button :url="route('home')">
Explorar Tienda
</x-mail::button>

Si tienes alguna duda, no dudes en contactarnos.

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>