<section x-data="{ codeSent: false, sending: false }">
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Actualizar Contraseña') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __('Para actualizar su contraseña, primero debe verificar su identidad mediante un código enviado a su correo electrónico.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Step 1: Send Code -->
        <div x-show="!codeSent">
             <x-secondary-button 
                type="button" 
                x-on:click="
                    sending = true;
                    axios.post('{{ route('profile.security.send-code') }}', { action: 'update_password' })
                        .then(response => {
                            codeSent = true;
                            Swal.fire({
                                icon: 'success',
                                title: 'Código Enviado',
                                text: response.data.message,
                                timer: 3000,
                                showConfirmButton: false
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al enviar el código. Por favor intente nuevamente.'
                            });
                            console.error(error);
                        })
                        .finally(() => sending = false);
                "
                x-bind:disabled="sending"
                class="mt-2"
            >
                <span x-text="sending ? 'Enviando...' : 'Enviar Código de Verificación'"></span>
            </x-secondary-button>
        </div>

        <!-- Step 2: Enter Code & New Password -->
        <div x-show="codeSent" style="display: none;">
            <div>
                <x-input-label for="verification_code" :value="__('Código de Verificación')" />
                <x-text-input id="verification_code" name="verification_code" type="text" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
                <p class="text-xs text-gray-500 mt-1">Revise su correo electrónico para obtener el código.</p>
            </div>

            <div class="mt-4">
                <x-input-label for="update_password_password" :value="__('Nueva Contraseña')" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 mt-6">
                <x-primary-button>{{ __('Actualizar Contraseña') }}</x-primary-button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 "
                    >{{ __('Contraseña Actualizada.') }}</p>
                @endif
            </div>
            
             <button type="button" x-on:click="codeSent = false" class="text-sm text-gray-500 underline mt-4 hover:text-gray-700">
                {{ __('Cancelar / Reenviar código') }}
            </button>
        </div>
    </form>
</section>
