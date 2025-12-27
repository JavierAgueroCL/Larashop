<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __('Actualice la información del perfil y la dirección de correo electrónico de su cuenta.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form 
        method="post" 
        action="{{ route('profile.update') }}" 
        class="mt-6 space-y-6" 
        x-data="{ 
            initialEmail: '{{ $user->email }}',
            currentEmail: '{{ old('email', $user->email) }}',
            codeSent: false, 
            sending: false,
            verificationCode: '',
            
            submitForm(e) {
                if (this.currentEmail !== this.initialEmail) {
                    // Email changed, trigger secure flow
                    if (!this.codeSent) {
                        e.preventDefault();
                        this.sendCode();
                    } else {
                        // Code sent, submit to secure route
                        if (!this.verificationCode) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atención',
                                text: 'Por favor ingrese el código de verificación.'
                            });
                            return;
                        }
                        
                        // Change action to secure update
                        this.$refs.form.action = '{{ route('profile.email.update') }}';
                        
                        // Ensure method is correct (PATCH is used by both routes, but let's double check)
                        // route('profile.update') uses PATCH
                        // route('profile.email.update') uses PATCH
                        
                        // Submit normally, the hidden verification_code input will be included
                    }
                } else {
                    // Normal update (Name only)
                    // Action remains {{ route('profile.update') }}
                }
            },
            
            sendCode() {
                this.sending = true;
                axios.post('{{ route('profile.security.send-code') }}', { action: 'update_email' })
                    .then(response => {
                        this.codeSent = true;
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
                    .finally(() => this.sending = false);
            }
        }"
        x-ref="form"
        @submit="submitForm"
    >
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" x-model="currentEmail" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 ">
                        {{ __('Su dirección de correo electrónico no está verificada.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ">
                            {{ __('Haga clic aquí para volver a enviar el correo electrónico de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 ">
                            {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Verification Code Input (Shown only when email changes and code sent) -->
        <div x-show="codeSent" style="display: none;" class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
            <x-input-label for="update_email_verification_code" :value="__('Código de Verificación')" />
            <p class="text-sm text-gray-600 mb-2">{{ __('Se ha enviado un código a su correo actual para confirmar el cambio.') }}</p>
            <x-text-input 
                id="update_email_verification_code" 
                name="verification_code" 
                type="text" 
                class="mt-1 block w-full" 
                x-model="verificationCode"
                placeholder="Ingrese el código"
            />
             <x-input-error class="mt-2" :messages="$errors->get('verification_code')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button 
                ::disabled="sending"
            >
                <span x-text="sending ? '{{ __('Enviando...') }}' : (codeSent && currentEmail !== initialEmail ? '{{ __('Verificar y Guardar') }}' : '{{ __('Guardar') }}')"></span>
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 "
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
