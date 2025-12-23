<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finalizar Compra') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('checkout.process') }}" method="POST"
                  x-data="{
                      // Address Logic
                      selectedAddress: '{{ old('shipping_address_id', $addresses->isNotEmpty() ? $addresses->first()->id : 'new') }}', 
                      showForm: {{ $addresses->isEmpty() || old('shipping_address_id') == 'new' ? 'true' : 'false' }},
                      addresses: {{ $addresses->toJson() }},
                      
                      isAuth: {{ Auth::check() ? 'true' : 'false' }},
                      
                      // Shipping Form
                      form: {
                          alias: '{{ old('shipping_address.alias') }}',
                          first_name: '{{ old('shipping_address.first_name', $user->first_name ?? '') }}',
                          last_name: '{{ old('shipping_address.last_name', $user->last_name ?? '') }}',
                          address_line_1: '{{ old('shipping_address.address_line_1') }}',
                          city: '{{ old('shipping_address.city') }}',
                          country_code: '{{ old('shipping_address.country_code', 'CL') }}',
                          phone: '{{ old('shipping_address.phone', $user->phone ?? '') }}'
                      },
                      
                      // Guest Billing Logic
                      useBillingForShipping: true,
                      billing: {
                          first_name: '{{ old('billing_address.first_name') }}',
                          last_name: '{{ old('billing_address.last_name') }}',
                          address_line_1: '{{ old('billing_address.address_line_1') }}',
                          city: '{{ old('billing_address.city') }}',
                          country_code: 'CL',
                          phone: '{{ old('billing_address.phone') }}'
                      },
                      
                      // Shipping & Totals Logic
                      carriers: {{ $carriers->toJson() }},
                      selectedShippingMethod: '{{ old('shipping_method', $carriers->first()->name ?? '') }}',
                      shippingCost: 0,
                      subtotal: {{ $totals['subtotal'] }},
                      tax: {{ $totals['tax'] }},
                      discount: {{ $totals['discount'] ?? 0 }},

                      get total() {
                          return (this.subtotal + this.tax + this.shippingCost - this.discount).toFixed(0);
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
                          this.form.country_code = 'CL';
                          this.form.phone = '{{ $user->phone ?? '' }}';
                      },
                      updateShipping() {
                          const carrier = this.carriers.find(c => c.name == this.selectedShippingMethod);
                          this.shippingCost = carrier ? parseFloat(carrier.calculated_cost) : 0;
                      },
                      syncBilling() {
                          if (!this.isAuth && this.useBillingForShipping) {
                              this.form.first_name = this.billing.first_name;
                              this.form.last_name = this.billing.last_name;
                              this.form.address_line_1 = this.billing.address_line_1;
                              this.form.city = this.billing.city;
                              this.form.country_code = this.billing.country_code;
                              this.form.phone = this.billing.phone;
                          }
                      },
                      init() {
                          if(this.selectedAddress !== 'new' && this.addresses.length > 0) {
                              this.populateForm(this.selectedAddress);
                          }
                          this.updateShipping();
                          
                          // Watch for billing changes to sync
                          this.$watch('billing', () => this.syncBilling());
                          this.$watch('useBillingForShipping', () => this.syncBilling());
                      }
                  }">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ __('¡Vaya! Algo salió mal.') }}</strong>
                        <span class="block sm:inline">{{ __('Por favor, revisa el formulario en busca de errores.') }}</span>
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

                        @guest
                            <!-- Billing Address (Guest Only) -->
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Datos de Facturación') }}</h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Correo Electrónico') }}</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Nombre') }}</label>
                                        <input type="text" name="billing_address[first_name]" x-model="billing.first_name" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Apellido') }}</label>
                                        <input type="text" name="billing_address[last_name]" x-model="billing.last_name" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Dirección') }}</label>
                                        <input type="text" name="billing_address[address_line_1]" x-model="billing.address_line_1" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Ciudad') }}</label>
                                        <input type="text" name="billing_address[city]" x-model="billing.city" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('País') }}</label>
                                        <input type="text" value="Chile" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
                                        <input type="hidden" name="billing_address[country_code]" value="CL">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Teléfono') }}</label>
                                        <input type="text" name="billing_address[phone]" x-model="billing.phone" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                </div>
                            </div>
                        @endguest

                        <!-- Shipping Address -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-900">{{ __('Dirección de envío') }}</h3>
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
                                            {{ __('Añadir Nueva Dirección') }}
                                        </button>
                                    </div>
                                @endif
                            @endauth

                            @guest
                                <div class="mb-6 flex items-center">
                                    <input id="use_billing" type="checkbox" x-model="useBillingForShipping" @change="syncBilling" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="use_billing" class="ml-2 block text-sm text-gray-900">
                                        {{ __('Usar los mismos datos de facturación para el envío') }}
                                    </label>
                                </div>
                            @endguest

                            <!-- Form Fields (Visible only if New Address selected OR (Guest AND !UseBilling)) -->
                            <div x-show="showForm && (!useBillingForShipping || isAuth)" x-transition.opacity class="space-y-4 pt-4 border-t border-gray-100">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Alias de la Dirección') }} <span class="text-gray-500 text-xs">(e.g., Casa, Trabajo)</span></label>
                                    <input type="text" name="shipping_address[alias]" x-model="form.alias" placeholder="Mi Casa" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Nombre') }}</label>
                                        <input type="text" name="shipping_address[first_name]" x-model="form.first_name" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Apellido') }}</label>
                                        <input type="text" name="shipping_address[last_name]" x-model="form.last_name" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Dirección') }}</label>
                                        <input type="text" name="shipping_address[address_line_1]" x-model="form.address_line_1" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Ciudad') }}</label>
                                        <input type="text" name="shipping_address[city]" x-model="form.city" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('País') }}</label>
                                        <input type="text" value="Chile" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
                                        <input type="hidden" name="shipping_address[country_code]" value="CL" x-model="form.country_code">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Teléfono') }}</label>
                                        <input type="text" name="shipping_address[phone]" x-model="form.phone" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <input type="hidden" name="shipping_address[state_province]" value="NA"> 
                                </div>
                            </div>
                            
                            <!-- Hidden inputs to submit copied data when form is hidden -->
                            <template x-if="!isAuth && useBillingForShipping">
                                <div>
                                    <input type="hidden" name="shipping_address[first_name]" :value="form.first_name">
                                    <input type="hidden" name="shipping_address[last_name]" :value="form.last_name">
                                    <input type="hidden" name="shipping_address[address_line_1]" :value="form.address_line_1">
                                    <input type="hidden" name="shipping_address[city]" :value="form.city">
                                    <input type="hidden" name="shipping_address[country_code]" :value="form.country_code">
                                    <input type="hidden" name="shipping_address[phone]" :value="form.phone">
                                    <input type="hidden" name="shipping_address[alias]" value="Billing Copy">
                                </div>
                            </template>

                        @guest
                        <div class="mt-6 border-t border-gray-200 pt-4" x-data="{ createAccount: false }">
                            <div class="flex items-center">
                                <input id="create_account" name="create_account" type="checkbox" value="1" x-model="createAccount" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="create_account" class="ml-2 block text-sm text-gray-900">
                                    {{ __('¿Crear una cuenta?') }}
                                </label>
                            </div>
                            
                            <div class="mt-4 space-y-4" x-show="createAccount" x-cloak>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Contraseña') }}</label>
                                    <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('password') border-red-500 @enderror">
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Confirmar Contraseña') }}</label>
                                    <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                </div>
                            </div>
                        </div>
                        @endguest
                        </div>

                        <!-- Shipping Method -->
                        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Método de Envío') }}</h3>
                            <div class="space-y-4">
                                @foreach($carriers as $carrier)
                                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:border-indigo-500 transition-colors"
                                           :class="selectedShippingMethod == '{{ $carrier->name }}' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : 'border-gray-300'">
                                        <input type="radio" 
                                               name="shipping_method" 
                                               value="{{ $carrier->name }}" 
                                               x-model="selectedShippingMethod"
                                               @change="updateShipping()"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <div class="ml-3 flex-1 flex justify-between">
                                            <div>
                                                <span class="block font-medium text-gray-900">{{ $carrier->display_name }}</span>
                                                <span class="block text-sm text-gray-500">{{ $carrier->delay }}</span>
                                            </div>
                                            <span class="font-bold text-gray-900">{{ number_format($carrier->calculated_cost, 0, ',', '.') }} $</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Método de Pago') }}</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="bank_transfer" name="payment_method" type="radio" value="bank_transfer" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="bank_transfer" class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ __('Transferencia Bancaria') }}
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="paypal" name="payment_method" type="radio" value="paypal" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="paypal" class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ __('PayPal') }}
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="transbank" name="payment_method" type="radio" value="transbank" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="transbank" class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ __('Webpay Plus (Transbank)') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="w-full md:w-1/3">
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-300 sticky top-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Resumen del pedido') }}</h3>
                            
                            <div class="space-y-4 mb-6">
                                @foreach($cart->items as $item)
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->quantity }}x {{ $item->product->name }}</div>
                                        </div>
                                        <div class="text-sm text-gray-600">{{ number_format($item->total, 0, ',', '.') }} $</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span>{{ __('Subtotal') }}</span>
                                    <span x-text="subtotal.toFixed(0) + ' $'">{{ number_format($totals['subtotal'], 0, ',', '.') }} $</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span>{{ __('Impuestos') }}</span>
                                    <span x-text="tax.toFixed(0) + ' $'">{{ number_format($totals['tax'], 0, ',', '.') }} $</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span>{{ __('Envío') }}</span>
                                    <span x-text="shippingCost.toFixed(0) + ' $'">0 $</span>
                                </div>
                                @if($totals['discount'] > 0)
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>{{ __('Descuento') }}</span>
                                        <span>-{{ number_format($totals['discount'], 0, ',', '.') }} $</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>{{ __('Total') }}</span>
                                    <span x-text="total + ' $'">{{ number_format($totals['total'], 0, ',', '.') }} $</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-6 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-bold transition-colors shadow-md">
                                {{ __('Realizar Pedido') }}
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>