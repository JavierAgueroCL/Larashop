<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Eliminar cuenta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Eliminar cuenta') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy.secure') }}" class="p-6" x-data="{ codeSent: false, sending: false }">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 ">
                {{ __('¿Está seguro de que desea eliminar su cuenta?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 ">
                {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. Por favor ingrese el código de verificación enviado a su correo para confirmar.') }}
            </p>

            <div class="mt-6">
                <!-- Step 1: Send Code -->
                <div x-show="!codeSent">
                    <x-secondary-button 
                        type="button" 
                        x-on:click="
                            sending = true;
                            axios.post('{{ route('profile.security.send-code') }}', { action: 'delete_account' })
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
                    >
                        <span x-text="sending ? 'Enviando...' : 'Enviar Código de Verificación'"></span>
                    </x-secondary-button>
                </div>

                <!-- Step 2: Enter Code -->
                <div x-show="codeSent" style="display: none;" class="mt-4">
                     <x-input-label for="verification_code_delete" value="{{ __('Código de Verificación') }}" class="sr-only" />

                    <x-text-input
                        id="verification_code_delete"
                        name="verification_code"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Código de Verificación') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('verification_code')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" x-show="codeSent">
                    {{ __('Eliminar Cuenta') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>