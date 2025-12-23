<div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-6 md:mb-0 border border-gray-300">
    <div class="p-4">
        <nav class="space-y-1">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group border-l-4 px-3 py-2 flex items-center text-sm font-medium transition-colors">
                <svg class="{{ request()->routeIs('dashboard') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                {{ __('Mis Pedidos') }}
            </a>

            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group border-l-4 px-3 py-2 flex items-center text-sm font-medium transition-colors">
                <svg class="{{ request()->routeIs('profile.edit') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ __('Perfil') }}
            </a>

            <a href="{{ route('dashboard.security') }}" class="{{ request()->routeIs('dashboard.security') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group border-l-4 px-3 py-2 flex items-center text-sm font-medium transition-colors">
                <svg class="{{ request()->routeIs('dashboard.security') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                {{ __('Seguridad') }}
            </a>

            <a href="{{ route('addresses.shipping') }}" class="{{ request()->routeIs('addresses.shipping') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group border-l-4 px-3 py-2 flex items-center text-sm font-medium transition-colors">
                <svg class="{{ request()->routeIs('addresses.shipping') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ __('Direcciones de Envío') }}
            </a>

            <a href="{{ route('addresses.billing') }}" class="{{ request()->routeIs('addresses.billing') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group border-l-4 px-3 py-2 flex items-center text-sm font-medium transition-colors">
                <svg class="{{ request()->routeIs('addresses.billing') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                {{ __('Dirección de Facturación') }}
            </a>
            
            <a href="{{ route('dashboard.help') }}" class="{{ request()->routeIs('dashboard.help') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900' }} group border-l-4 px-3 py-2 flex items-center text-sm font-medium transition-colors">
                <svg class="{{ request()->routeIs('dashboard.help') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }} flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('Ayuda y Contacto') }}
            </a>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="border-transparent text-gray-700 hover:bg-gray-50 hover:text-gray-900 group border-l-4 px-3 py-2 flex items-center text-sm font-medium transition-colors cursor-pointer">
                    <svg class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Cerrar Sesión') }}
                </a>
            </form>
        </nav>
    </div>
</div>