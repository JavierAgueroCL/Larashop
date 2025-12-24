<x-guest-layout>
 <form method="POST" action="{{ route('register') }}">
 @csrf

 <!-- Name -->
 <div>
 <x-input-label for="name" :value="__('Nombre de Usuario')" />
 <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
 <x-input-error :messages="$errors->get('name')" class="mt-2" />
 </div>

 <!-- First Name -->
 <div class="mt-4">
 <x-input-label for="first_name" :value="__('Nombre')" />
 <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autocomplete="given-name" />
 <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
 </div>

 <!-- Last Name -->
 <div class="mt-4">
 <x-input-label for="last_name" :value="__('Apellidos')" />
 <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
 <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
 </div>

 <!-- Email Address -->
 <div class="mt-4">
 <x-input-label for="email" :value="__('Correo Electrónico')" />
 <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
 <x-input-error :messages="$errors->get('email')" class="mt-2" />
 </div>

 <!-- Password -->
 <div class="mt-4">
 <x-input-label for="password" :value="__('Contraseña')" />

 <x-text-input id="password" class="block mt-1 w-full"
 type="password"
 name="password"
 required autocomplete="new-password" />

 <x-input-error :messages="$errors->get('password')" class="mt-2" />
 </div>

 <!-- Confirm Password -->
 <div class="mt-4">
 <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />

 <x-text-input id="password_confirmation" class="block mt-1 w-full"
 type="password"
 name="password_confirmation" required autocomplete="new-password" />

 <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
 </div>

 <div class="flex justify-between mt-4">
 <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 " href="{{ route('login') }}">
 {{ __('Iniciar sesión') }}
 </a>

         <x-primary-button class="ms-4">
             {{ __('Registrarse') }}
         </x-primary-button>
     </div>
 
     <!-- Social Register -->
     <div class="mt-6">
         <div class="relative">
             <div class="absolute inset-0 flex items-center">
                 <div class="w-full border-t border-gray-300"></div>
             </div>
             <div class="relative flex justify-center text-sm">
                 <span class="px-2 bg-white text-gray-500">O regístrate con</span>
             </div>
         </div>
 
         <div class="mt-6 grid grid-cols-1 gap-3">
             <a href="{{ route('login.google') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                 <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                     <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                 </svg>
                 <span class="ml-2">Google</span>
             </a>
         </div>
     </div>
 </form>
 </x-guest-layout>
