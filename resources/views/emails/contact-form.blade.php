<x-mail::message>
# Nuevo Mensaje de Contacto

Has recibido un nuevo mensaje desde el formulario de contacto.

**Nombre:** {{ $data['name'] }}
**Email:** {{ $data['email'] }}
**Asunto:** {{ $data['subject'] }}

**Mensaje:**
{{ $data['message'] }}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>