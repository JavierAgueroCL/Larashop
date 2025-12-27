<x-mail::message>
# Código de Verificación

Utilice el siguiente código para completar su solicitud de **{{ str_replace('_', ' ', $action) }}**.

<x-mail::panel>
{{ $code }}
</x-mail::panel>

Este código expirará en 10 minutos.

Si usted no solicitó esto, por favor ignore este correo.

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>