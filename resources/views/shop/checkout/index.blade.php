<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Checkout') }}
 </h2>
 </x-slot>

 <div class="py-12 bg-gray-50">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 <form action="{{ route('checkout.process') }}" method="POST">
 @csrf

 @if ($errors->any())
 <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
 <strong class="font-bold">{{ __('Whoops! Something went wrong.') }}</strong>
 <span class="block sm:inline">{{ __('Please check the form for errors.') }}</span>
 <ul class="mt-2 list-disc list-inside text-sm text-red-600">
 @foreach ($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 <div class="flex flex-col md:flex-row gap-8">
 
 <!-- Left Column: Address & Payment -->
 <div class="w-full md:w-2/3 space-y-6">
 
                         <!-- Shipping Address -->
                         <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100" 
                              x-data="{
                                 selectedAddress: '{{ old('shipping_address_id', $addresses->isNotEmpty() ? $addresses->first()->id : 'new') }}', 
                                 showForm: {{ $addresses->isEmpty() || old('shipping_address_id') == 'new' ? 'true' : 'false' }},
                                 addresses: {{ $addresses->toJson() }},
                                form: {
                                    alias: '{{ old('shipping_address.alias') }}',
                                    first_name: '{{ old('shipping_address.first_name', $user->first_name ?? '') }}',
                                    last_name: '{{ old('shipping_address.last_name', $user->last_name ?? '') }}',
                                    address_line_1: '{{ old('shipping_address.address_line_1') }}',
                                    city: '{{ old('shipping_address.city') }}',
                                    postal_code: '{{ old('shipping_address.postal_code') }}',
                                    country_code: '{{ old('shipping_address.country_code', 'ES') }}',
                                    phone: '{{ old('shipping_address.phone', $user->phone ?? '') }}'
                                },
                                selectAddress(id) {
                                    this.selectedAddress = id;
                                    if (id === 'new') {
                                        this.showForm = true;
                                        this.clearForm();
                                    } else {
                                        this.showForm = false;
                                        this.populateForm(id);
                                    }
                                },
                                populateForm(id) {
                                    const addr = this.addresses.find(a => a.id == id);
                                    if (addr) {
                                        this.form.alias = addr.alias;
                                        this.form.first_name = addr.first_name;
                                        this.form.last_name = addr.last_name;
                                        this.form.address_line_1 = addr.address_line_1;
                                        this.form.city = addr.city;
                                        this.form.postal_code = addr.postal_code;
                                        this.form.country_code = addr.country_code;
                                        this.form.phone = addr.phone;
                                    }
                                },
                                clearForm() {
                                    this.form.alias = '';
                                    this.form.first_name = '{{ $user->first_name ?? '' }}';
                                    this.form.last_name = '{{ $user->last_name ?? '' }}';
                                    this.form.address_line_1 = '';
                                    this.form.city = '';
                                    this.form.postal_code = '';
                                    this.form.country_code = 'ES';
                                    this.form.phone = '{{ $user->phone ?? '' }}';
                                },
                                 init() {
                                     if(this.selectedAddress !== 'new' && this.addresses.length > 0) {
                                         this.populateForm(this.selectedAddress);
                                     }
                                 }
                              }">
                             <div class="flex justify-between items-center mb-6">
                                 <h3 class="text-lg font-bold text-gray-900">{{ __('Shipping Address') }}</h3>
                             </div>
                             
                             <input type="hidden" name="shipping_address_id" :value="selectedAddress">
 
                             @auth
                                 @if($addresses->isNotEmpty())
                                     <div class="grid grid-cols-1 gap-4 mb-6">
                                         @foreach($addresses as $address)
                                             <label class="relative flex items-start p-4 border rounded-lg cursor-pointer hover:border-indigo-500 transition-colors" 
                                                    :class="selectedAddress == {{ $address->id }} ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : 'border-gray-200'">
                                                 <div class="flex items-center h-5">
                                                     <input type="radio" 
                                                            name="address_selection" 
                                                            value="{{ $address->id }}" 
                                                            @click="selectAddress({{ $address->id }})"
                                                            :checked="selectedAddress == {{ $address->id }}"
                                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                                 </div>
                                                 <div class="ml-3 text-sm">
                                                     <span class="block font-bold text-gray-900">{{ $address->alias ?? 'Address' }}</span>
                                                     <span class="block text-gray-600">{{ $address->address_line_1 }}, {{ $address->city }}</span>
                                                     <span class="block text-gray-500">{{ $address->country_code }} - {{ $address->phone }}</span>
                                                 </div>
                                             </label>
                                         @endforeach
                                     </div>
 
                                     <div class="mb-6">
                                         <button type="button" 
                                                 @click="selectAddress('new')"
                                                 class="flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">
                                             <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                             {{ __('Add New Address') }}
                                         </button>
                                     </div>
                                 @endif
                             @endauth
 
                             @guest
                                 <div class="mb-4">
                                     <label class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                                     <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('email') border-red-500 @enderror">
                                     @error('email')
                                         <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                     @enderror
                                 </div>
                             @endguest
 
                                                         <!-- Form Fields (Visible only if New Address selected or Guest) -->
                                                         <div x-show="showForm" x-transition.opacity class="space-y-4 pt-4 border-t border-gray-100">
                                                             <div>
                                                                 <label class="block text-sm font-medium text-gray-700">{{ __('Address Alias') }} <span class="text-gray-500 text-xs">(e.g., Home, Work)</span></label>
                                                                 <input type="text" name="shipping_address[alias]" x-model="form.alias" placeholder="My Home" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('shipping_address.alias') border-red-500 @enderror">
                                                                 @error('shipping_address.alias')
                                                                     <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                 @enderror
                                                             </div>
                             
                                                             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">                                     <div>
                                         <label class="block text-sm font-medium text-gray-700">{{ __('First Name') }}</label>
                                         <input type="text" name="shipping_address[first_name]" x-model="form.first_name" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('shipping_address.first_name') border-red-500 @enderror">
                                         @error('shipping_address.first_name')
                                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                         @enderror
                                     </div>
                                     <div>
                                         <label class="block text-sm font-medium text-gray-700">{{ __('Last Name') }}</label>
                                         <input type="text" name="shipping_address[last_name]" x-model="form.last_name" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('shipping_address.last_name') border-red-500 @enderror">
                                         @error('shipping_address.last_name')
                                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                         @enderror
                                     </div>
                                     <div class="md:col-span-2">
                                         <label class="block text-sm font-medium text-gray-700">{{ __('Address') }}</label>
                                         <input type="text" name="shipping_address[address_line_1]" x-model="form.address_line_1" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('shipping_address.address_line_1') border-red-500 @enderror">
                                         @error('shipping_address.address_line_1')
                                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                         @enderror
                                     </div>
                                     <div>
                                         <label class="block text-sm font-medium text-gray-700">{{ __('City') }}</label>
                                         <input type="text" name="shipping_address[city]" x-model="form.city" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('shipping_address.city') border-red-500 @enderror">
                                         @error('shipping_address.city')
                                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                         @enderror
                                     </div>
                                     <div>
                                         <label class="block text-sm font-medium text-gray-700">{{ __('Postal Code') }}</label>
                                         <input type="text" name="shipping_address[postal_code]" x-model="form.postal_code" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('shipping_address.postal_code') border-red-500 @enderror">
                                         @error('shipping_address.postal_code')
                                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                         @enderror
                                     </div>
                                     <div>
                                         <label class="block text-sm font-medium text-gray-700">{{ __('Country') }}</label>
                                         <select name="shipping_address[country_code]" x-model="form.country_code" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('shipping_address.country_code') border-red-500 @enderror">
                                             <option value="ES">Spain</option>
                                             <option value="US">United States</option>
                                         </select>
                                         @error('shipping_address.country_code')
                                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                         @enderror
                                     </div>
                                     <div>
                                         <label class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                                         <input type="text" name="shipping_address[phone]" x-model="form.phone" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('shipping_address.phone') border-red-500 @enderror">
                                         @error('shipping_address.phone')
                                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                         @enderror
                                     </div>
                                     <input type="hidden" name="shipping_address[state_province]" value="NA"> 
                                 </div>
                             </div>
 @guest
 <div class="mt-6 border-t border-gray-200 pt-4" x-data="{ createAccount: false }">
 <div class="flex items-center">
 <input id="create_account" name="create_account" type="checkbox" value="1" x-model="createAccount" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
 <label for="create_account" class="ml-2 block text-sm text-gray-900">
 {{ __('Create an account?') }}
 </label>
 </div>
 
 <div class="mt-4 space-y-4" x-show="createAccount" x-cloak>
 <div>
 <label class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
 <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('password') border-red-500 @enderror">
 @error('password')
 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
 @enderror
 </div>
 <div>
 <label class="block text-sm font-medium text-gray-700">{{ __('Confirm Password') }}</label>
 <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
 </div>
 </div>
 </div>
 @endguest
 </div>

 <!-- Payment Method -->
 <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
 <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Payment Method') }}</h3>
 <div class="space-y-4">
 <div class="flex items-center">
 <input id="bank_transfer" name="payment_method" type="radio" value="bank_transfer" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
 <label for="bank_transfer" class="ml-3 block text-sm font-medium text-gray-700">
 {{ __('Bank Transfer') }}
 </label>
 </div>
 <div class="flex items-center">
 <input id="paypal" name="payment_method" type="radio" value="paypal" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
 <label for="paypal" class="ml-3 block text-sm font-medium text-gray-700">
 {{ __('PayPal') }}
 </label>
 </div>
 </div>
 </div>

 </div>

 <!-- Right Column: Order Summary -->
 <div class="w-full md:w-1/3">
 <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-300 sticky top-6">
 <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Order Summary') }}</h3>
 
 <div class="space-y-4 mb-6">
 @foreach($cart->items as $item)
 <div class="flex justify-between items-start">
 <div class="flex items-center">
 <div class="text-sm font-medium text-gray-900">{{ $item->quantity }}x {{ $item->product->name }}</div>
 </div>
 <div class="text-sm text-gray-600">{{ number_format($item->total, 2) }} €</div>
 </div>
 @endforeach
 </div>

 <div class="border-t border-gray-200 pt-4 space-y-3">
 <div class="flex justify-between text-sm text-gray-700">
 <span>{{ __('Subtotal') }}</span>
 <span>{{ number_format($totals['subtotal'], 2) }} €</span>
 </div>
 <div class="flex justify-between text-sm text-gray-700">
 <span>{{ __('Tax') }}</span>
 <span>{{ number_format($totals['tax'], 2) }} €</span>
 </div>
 <div class="flex justify-between text-lg font-bold text-gray-900">
 <span>{{ __('Total') }}</span>
 <span>{{ number_format($totals['total'], 2) }} €</span>
 </div>
 </div>

 <button type="submit" class="w-full mt-6 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-bold transition-colors shadow-md">
 {{ __('Place Order') }}
 </button>
 </div>
 </div>

 </div>
 </form>
 </div>
 </div>
</x-app-layout>