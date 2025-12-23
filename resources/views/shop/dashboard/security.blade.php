<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración de Seguridad') }}
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
                <div class="w-full md:w-3/4 space-y-6">
                    
                    <!-- Two Factor Authentication -->
                    <div class="p-4 sm:p-8 bg-white shadow-md sm:rounded-lg border border-gray-300">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Autenticación de Dos Factores') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Añade seguridad adicional a tu cuenta utilizando la autenticación de dos factores.') }}
                                </p>
                            </header>

                            <div class="mt-6">
                                @if(! auth()->user()->two_factor_secret)
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ __('No has habilitado la autenticación de dos factores.') }}
                                    </h3>

                                    <p class="mt-3 max-w-xl text-sm text-gray-600">
                                        {{ __('Cuando la autenticación de dos factores está habilitada, se te pedirá un token seguro y aleatorio durante la autenticación. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.') }}
                                    </p>

                                    <form method="POST" action="{{ route('two-factor.enable') }}" class="mt-6">
                                        @csrf
                                        <x-primary-button>
                                            {{ __('Habilitar') }}
                                        </x-primary-button>
                                    </form>
                                @else
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ __('Has habilitado la autenticación de dos factores.') }}
                                    </h3>

                                    <p class="mt-3 max-w-xl text-sm text-gray-600">
                                        {{ __('Cuando la autenticación de dos factores está habilitada, se te pedirá un token seguro y aleatorio durante la autenticación. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.') }}
                                    </p>

                                    @if (session('status') == 'two-factor-authentication-enabled')
                                        <div class="mt-4">
                                            <p class="font-semibold text-sm text-gray-600">
                                                {{ __('La autenticación de dos factores está ahora habilitada. Escanea el siguiente código QR usando la aplicación de autenticación de tu teléfono.') }}
                                            </p>

                                            <div class="mt-4 p-2 inline-block bg-white">
                                                {!! request()->user()->twoFactorQrCodeSvg() !!}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-6">
                                        <div class="mt-4 max-w-xl text-sm text-gray-600 bg-gray-100 p-4 rounded-lg">
                                            <p class="font-semibold mb-2">
                                                {{ __('Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Se pueden utilizar para recuperar el acceso a tu cuenta si pierdes tu dispositivo de autenticación de dos factores.') }}
                                            </p>

                                            <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-200 rounded-lg">
                                                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                                    <div>{{ $code }}</div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="mt-5 flex gap-4">
                                            <!-- Regenerate Codes -->
                                            <form method="POST" action="{{ url('user/two-factor-recovery-codes') }}">
                                                @csrf
                                                <x-secondary-button type="submit">
                                                    {{ __('Regenerar Códigos de Recuperación') }}
                                                </x-secondary-button>
                                            </form>

                                            <!-- Disable 2FA -->
                                            <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit">
                                                    {{ __('Deshabilitar') }}
                                                </x-danger-button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>

                    <!-- Browser Sessions -->
                    <div class="p-4 sm:p-8 bg-white shadow-md sm:rounded-lg border border-gray-300">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Sesiones de Navegador') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Gestiona y cierra las sesiones activas en otros navegadores y dispositivos.') }}
                                </p>
                            </header>

                            <div class="mt-6 text-sm text-gray-600">
                                {{ __('Si es necesario, puedes cerrar todas las demás sesiones de navegador en todos tus dispositivos. Algunas de tus sesiones recientes se enumeran a continuación; sin embargo, esta lista puede no ser exhaustiva. Si crees que tu cuenta ha sido comprometida, también deberías actualizar tu contraseña.') }}
                            </div>

                            @if (count($sessions) > 0)
                                <div class="mt-5 space-y-6">
                                    <!-- Sessions List -->
                                    @foreach ($sessions as $session)
                                        <div class="flex items-center">
                                            <div>
                                                @if ($session->agent['is_desktop'])
                                                    <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 17.257V17.25m-9 0V18a2.25 2.25 0 002.25 2.25h3a2.25 2.25 0 002.25-2.25v-.75m0-6v-3a2.25 2.25 0 00-2.25-2.25h-3A2.25 2.25 0 009 6v3m0 0h6m-6 0a2.25 2.25 0 012.25-2.25h3a2.25 2.25 0 012.25 2.25v7.5m-9 0h9" />
                                                    </svg>
                                                @else
                                                    <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                                    </svg>
                                                @endif
                                            </div>

                                            <div class="ml-3">
                                                <div class="text-sm text-gray-600">
                                                    {{ $session->agent['platform'] ? $session->agent['platform'] : 'Unknown' }} - {{ $session->agent['browser'] ? $session->agent['browser'] : 'Unknown' }}
                                                </div>

                                                <div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $session->ip_address }}, 

                                                        @if ($session->is_current_device)
                                                            <span class="text-green-500 font-semibold">{{ __('Este dispositivo') }}</span>
                                                        @else
                                                            {{ __('Última actividad') }} {{ $session->last_active }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center mt-5">
                                <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'logout-other-browser-sessions')">
                                    {{ __('Cerrar Otras Sesiones de Navegador') }}
                                </x-primary-button>

                                <x-modal name="logout-other-browser-sessions" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                    <form method="post" action="{{ route('dashboard.security.logout-others') }}" class="p-6">
                                        @csrf
                                        @method('delete')

                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Cerrar Otras Sesiones de Navegador') }}
                                        </h2>

                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __('Por favor, introduce tu contraseña para confirmar que deseas cerrar todas las demás sesiones de navegador en todos tus dispositivos.') }}
                                        </p>

                                        <div class="mt-6">
                                            <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />

                                            <x-text-input
                                                id="password"
                                                name="password"
                                                type="password"
                                                class="mt-1 block w-3/4"
                                                placeholder="{{ __('Contraseña') }}"
                                            />

                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div class="mt-6 flex justify-end">
                                            <x-secondary-button x-on:click="$dispatch('close')">
                                                {{ __('Cancelar') }}
                                            </x-secondary-button>

                                            <x-primary-button class="ml-3">
                                                {{ __('Cerrar Otras Sesiones de Navegador') }}
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </x-modal>
                            </div>
                        </section>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
