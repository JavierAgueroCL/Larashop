<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finalizar Compra') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('checkout.process') }}" method="POST"
                  x-data='{
                      // Address Logic
                      selectedAddress: "{{ old("shipping_address_id", $addresses->isNotEmpty() ? $addresses->first()->id : "new") }}", 
                      showForm: {{ $addresses->isEmpty() || old("shipping_address_id") == "new" ? "true" : "false" }},
                      addresses: {{ $addresses->toJson() }},
                      regions: {{ $regions->toJson() }},
                      
                      isAuth: {{ Auth::check() ? "true" : "false" }},
                      
                      // Shipping Form
                      form: {
                          alias: "{{ old("shipping_address.alias") }}",
                          first_name: "{{ old("shipping_address.first_name", $user->first_name ?? "") }}",
                          last_name: "{{ old("shipping_address.last_name", $user->last_name ?? "") }}",
                          address_line_1: "{{ old("shipping_address.address_line_1") }}",
                          address_line_2: "{{ old("shipping_address.address_line_2") }}",
                          region_id: "{{ old("shipping_address.region_id") }}",
                          comuna_id: "{{ old("shipping_address.comuna_id") }}",
                          country_code: "{{ old("shipping_address.country_code", "CL") }}",
                          phone: "{{ old("shipping_address.phone", $user->phone ?? "") }}"
                      },
                      shippingComunas: [],
                      
                      // Guest Billing Logic
                      useBillingForShipping: true,
                      billing: {
                          first_name: "{{ old("billing_address.first_name") }}",
                          last_name: "{{ old("billing_address.last_name") }}",
                          rut: "{{ old("billing_address.rut") }}",
                          document_type: "{{ old("billing_address.document_type", "boleta") }}",
                          company: "{{ old("billing_address.company") }}",
                          business_activity: "{{ old("billing_address.business_activity") }}",
                          address_line_1: "{{ old("billing_address.address_line_1") }}",
                          address_line_2: "{{ old("billing_address.address_line_2") }}",
                          region_id: "{{ old("billing_address.region_id") }}",
                          comuna_id: "{{ old("billing_address.comuna_id") }}",
                          country_code: "CL",
                          phone: "{{ old("billing_address.phone") }}"
                      },
                      billingComunas: [],
                      
                      // Shipping & Totals Logic
                      carriers: {{ $carriers->toJson() }},
                      selectedShippingMethod: "{{ old("shipping_method", $carriers->first()->name ?? "") }}",
                      shippingCost: 0,
                      isLoadingShipping: false,
                      subtotal: {{ $totals["subtotal"] }},
                      tax: {{ $totals["tax"] }},
                      discount: {{ $totals["discount"] ?? 0 }},

                      formatCurrency(val) {
                          return new Intl.NumberFormat("es-CL", { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(val);
                      },

                      formatRut(value) {
                          if (!value) return "";
                          let rut = value.replace(/[^0-9kK]/g, "");
                          if (rut.length < 2) return rut;
                          let dv = rut.slice(-1);
                          let body = rut.slice(0, -1);
                          body = body.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                          return body + "-" + dv.toUpperCase();
                      },

                      get total() {
                          return (this.subtotal + this.tax + this.shippingCost - this.discount);
                      },

                      selectAddress(id) {
                          this.selectedAddress = id;
                          if (id === "new") {
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
                              this.form.address_line_2 = addr.address_line_2;
                              this.form.region_id = addr.region_id;
                              this.form.comuna_id = addr.comuna_id;
                              this.form.country_code = addr.country_code;
                              this.form.phone = addr.phone;
                          }
                      },
                      clearForm() {
                          this.form.alias = "";
                          this.form.first_name = "{{ $user->first_name ?? "" }}";
                          this.form.last_name = "{{ $user->last_name ?? "" }}";
                          this.form.address_line_1 = "";
                          this.form.address_line_2 = "";
                          this.form.region_id = "";
                          this.form.comuna_id = "";
                          this.form.country_code = "CL";
                          this.form.phone = "{{ $user->phone ?? "" }}";
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
                              this.form.address_line_2 = this.billing.address_line_2;
                              this.form.region_id = this.billing.region_id;
                              this.form.comuna_id = this.billing.comuna_id;
                              this.form.country_code = this.billing.country_code;
                              this.form.phone = this.billing.phone;
                          }
                      },
                      fetchComunas(regionId, type) {
                          if (!regionId) return;
                          fetch(`/locations/regions/${regionId}/comunas`)
                              .then(res => res.json())
                              .then(data => {
                                  if (type === "billing") {
                                      this.billingComunas = data;
                                  } else {
                                      this.shippingComunas = data;
                                  }
                              });
                      },
                      fetchCarriers() {
                          const comunaId = this.form.comuna_id;
                          if (!comunaId) return;
                          
                          this.shippingCost = 0;
                          this.isLoadingShipping = true;

                          fetch("{{ route("checkout.calculate_shipping") }}", {
                              method: "POST",
                              headers: {
                                  "Content-Type": "application/json",
                                  "X-CSRF-TOKEN": "{{ csrf_token() }}"
                              },
                              body: JSON.stringify({ comuna_id: comunaId })
                          })
                          .then(res => res.json())
                          .then(data => {
                              this.carriers = data.carriers;
                              
                              // Select first available or keep current if exists
                              const exists = this.carriers.find(c => c.name == this.selectedShippingMethod);
                              if (!exists && this.carriers.length > 0) {
                                  this.selectedShippingMethod = this.carriers[0].name;
                              } else if (this.carriers.length === 0) {
                                  this.selectedShippingMethod = "";
                              }
                              
                              this.updateShipping();
                          })
                          .catch(error => console.error("Error fetching rates:", error))
                          .finally(() => {
                              this.isLoadingShipping = false;
                          });
                      },
                      init() {
                          if(this.selectedAddress !== "new" && this.addresses.length > 0) {
                              this.populateForm(this.selectedAddress);
                              // If populated, trigger fetch if comuna exists
                              if(this.form.comuna_id) this.fetchCarriers();
                          }
                          this.updateShipping();
                          
                          // Watchers
                          this.$watch("billing.region_id", val => {
                              if(val) this.fetchComunas(val, "billing");
                              else this.billingComunas = [];
                              this.billing.comuna_id = "";
                              this.syncBilling();
                          });
                          
                          this.$watch("form.region_id", val => {
                              if(val) this.fetchComunas(val, "shipping");
                              else this.shippingComunas = [];
                              // Reset carrier when region changes (implies comuna change needed)
                              this.carriers = []; 
                              this.selectedShippingMethod = "";
                              this.shippingCost = 0;
                          });

                          // Watch for Shipping Comuna Change to fetch rates
                          this.$watch("form.comuna_id", val => {
                              if(val) this.fetchCarriers();
                          });

                          this.$watch("billing", () => this.syncBilling(), { deep: true });
                          this.$watch("useBillingForShipping", () => this.syncBilling());

                          // Initial Fetch
                          if(this.billing.region_id) this.fetchComunas(this.billing.region_id, "billing");
                          if(this.form.region_id) this.fetchComunas(this.form.region_id, "shipping");
                      }
                  }'>
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
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    
                                    <!-- Document Type -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Tipo de Documento') }}</label>
                                        <div class="flex items-center gap-4">
                                            <label class="flex items-center">
                                                <input type="radio" name="billing_address[document_type]" value="boleta" x-model="billing.document_type" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                                <span class="ml-2 text-sm text-gray-700">{{ __('Boleta') }}</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="billing_address[document_type]" value="factura" x-model="billing.document_type" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                                <span class="ml-2 text-sm text-gray-700">{{ __('Factura') }}</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('RUT') }}</label>
                                        <input type="text" name="billing_address[rut]" x-model="billing.rut" @input="billing.rut = formatRut($event.target.value)" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>

                                    <!-- Company (Conditional) -->
                                    <div x-show="billing.document_type === 'factura'">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Razón Social') }}</label>
                                        <input type="text" name="billing_address[company]" x-model="billing.company" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>

                                    <!-- Giro (Conditional) -->
                                    <div x-show="billing.document_type === 'factura'" class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Giro') }}</label>
                                        <input type="text" name="billing_address[business_activity]" x-model="billing.business_activity" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Nombre') }}</label>
                                        <input type="text" name="billing_address[first_name]" x-model="billing.first_name" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Apellido') }}</label>
                                        <input type="text" name="billing_address[last_name]" x-model="billing.last_name" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Teléfono') }}</label>
                                        <input type="text" name="billing_address[phone]" x-model="billing.phone" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Correo Electrónico') }}</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900 @error('email') border-red-500 @enderror">
                                        @error('email')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Country Hidden -->
                                    <input type="hidden" name="billing_address[country_code]" value="CL">
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Región') }}</label>
                                        <select name="billing_address[region_id]" x-model="billing.region_id" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                            <option value="">{{ __('Seleccione') }}</option>
                                            <template x-for="region in regions" :key="region.id">
                                                <option :value="region.id" x-text="region.region"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Comuna') }}</label>
                                        <select name="billing_address[comuna_id]" x-model="billing.comuna_id" :disabled="!billingComunas.length" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                            <option value="">{{ __('Seleccione') }}</option>
                                            <template x-for="comuna in billingComunas" :key="comuna.id">
                                                <option :value="comuna.id" x-text="comuna.comuna"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Dirección (Calle y N°)') }}</label>
                                        <input type="text" name="billing_address[address_line_1]" x-model="billing.address_line_1" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Departamento / Oficina / Casa (Opcional)') }}</label>
                                        <input type="text" name="billing_address[address_line_2]" x-model="billing.address_line_2" @input="syncBilling" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                </div>
                            </div>
                        @endguest

                        <!-- Shipping Address -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-900">{{ __('Dirección de Envío') }}</h3>
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
                                                    <span class="block text-gray-600">{{ $address->address_line_1 }}, {{ $address->comuna->comuna ?? '' }}</span>
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

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Departamento / Oficina / Casa (Opcional)') }}</label>
                                        <input type="text" name="shipping_address[address_line_2]" x-model="form.address_line_2" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Región') }}</label>
                                        <select name="shipping_address[region_id]" x-model="form.region_id" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                            <option value="">{{ __('Seleccione') }}</option>
                                            <template x-for="region in regions" :key="region.id">
                                                <option :value="region.id" x-text="region.region"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Comuna') }}</label>
                                        <select name="shipping_address[comuna_id]" x-model="form.comuna_id" :disabled="!shippingComunas.length" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                            <option value="">{{ __('Seleccione') }}</option>
                                            <template x-for="comuna in shippingComunas" :key="comuna.id">
                                                <option :value="comuna.id" x-text="comuna.comuna"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="hidden">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('País') }}</label>
                                        <input type="text" value="Chile" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
                                        <input type="hidden" name="shipping_address[country_code]" value="CL" x-model="form.country_code">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ __('Teléfono') }}</label>
                                        <input type="text" name="shipping_address[phone]" x-model="form.phone" class="mt-1 block w-full rounded-md border-gray-300 text-gray-900">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden inputs to submit copied data when form is hidden -->
                            <template x-if="!isAuth && useBillingForShipping">
                                <div>
                                    <input type="hidden" name="shipping_address[first_name]" :value="form.first_name">
                                    <input type="hidden" name="shipping_address[last_name]" :value="form.last_name">
                                    <input type="hidden" name="shipping_address[address_line_1]" :value="form.address_line_1">
                                    <input type="hidden" name="shipping_address[address_line_2]" :value="form.address_line_2">
                                    <input type="hidden" name="shipping_address[region_id]" :value="form.region_id">
                                    <input type="hidden" name="shipping_address[comuna_id]" :value="form.comuna_id">
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
                                <template x-if="isLoadingShipping">
                                    <div class="flex items-center justify-center p-4">
                                        <svg class="animate-spin h-5 w-5 text-indigo-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-gray-600 font-medium">{{ __('Obteniendo precios de envío...') }}</span>
                                    </div>
                                </template>

                                <template x-if="!isLoadingShipping && carriers.length === 0">
                                    <p class="text-sm text-gray-500 italic">{{ __('Por favor, ingrese su dirección completa para ver las opciones de envío.') }}</p>
                                </template>
                                
                                <template x-for="carrier in carriers" :key="carrier.name">
                                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:border-indigo-500 transition-colors"
                                           :class="selectedShippingMethod == carrier.name ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : 'border-gray-300'"
                                           x-show="!isLoadingShipping">
                                        <input type="radio" 
                                               name="shipping_method" 
                                               :value="carrier.name" 
                                               x-model="selectedShippingMethod"
                                               @change="updateShipping()"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <div class="ml-3 flex-1 flex justify-between">
                                            <div>
                                                <span class="block font-medium text-gray-900" x-text="carrier.display_name"></span>
                                                <span class="block text-sm text-gray-500" x-text="carrier.delay"></span>
                                            </div>
                                            <span class="font-bold text-gray-900" x-text="'$ ' + formatCurrency(carrier.calculated_cost)"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Método de Pago') }}</h3>
                            <div class="space-y-4">
                                @if(config('payment.gateways.transbank.enabled'))
                                <div class="flex items-center">
                                    <input id="transbank" name="payment_method" type="radio" value="transbank" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="transbank" class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ __('Webpay Plus (Transbank)') }}
                                    </label>
                                </div>
                                @endif

                                @if(config('payment.gateways.bank_transfer.enabled'))
                                <div class="flex items-center">
                                    <input id="bank_transfer" name="payment_method" type="radio" value="bank_transfer" {{ !config('payment.gateways.transbank.enabled') ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="bank_transfer" class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ __('Transferencia Bancaria') }}
                                    </label>
                                </div>
                                @endif

                                @if(config('payment.gateways.paypal.enabled'))
                                <div class="flex items-center">
                                    <input id="paypal" name="payment_method" type="radio" value="paypal" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="paypal" class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ __('PayPal') }}
                                    </label>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="w-full md:w-1/3">
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-300 sticky top-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Resumen del Pedido') }}</h3>
                            
                            <div class="space-y-4 mb-6">
                                @foreach($cart->items as $item)
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->quantity }}x {{ $item->product->name }}</div>
                                        </div>
                                        <div class="text-sm text-gray-600">$ {{ number_format($item->total, 0, ',', '.') }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span>{{ __('Subtotal') }}</span>
                                    <span x-text="'$ ' + formatCurrency(subtotal)">$ {{ number_format($totals['subtotal'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span>{{ __('Impuestos') }}</span>
                                    <span x-text="'$ ' + formatCurrency(tax)">$ {{ number_format($totals['tax'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span>{{ __('Envío') }}</span>
                                    <span x-text="'$ ' + formatCurrency(shippingCost)">$ 0</span>
                                </div>
                                @if($totals['discount'] > 0)
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>{{ __('Descuento') }}</span>
                                        <span x-text="'-$ ' + formatCurrency(discount)">-$ {{ number_format($totals['discount'], 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>{{ __('Total') }}</span>
                                    <span x-text="'$ ' + formatCurrency(total)">$ {{ number_format($totals['total'], 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" 
                                    :disabled="isLoadingShipping || !selectedShippingMethod"
                                    :class="{'opacity-50 cursor-not-allowed': isLoadingShipping || !selectedShippingMethod}"
                                    class="w-full mt-6 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-bold transition-colors shadow-md">
                                {{ __('Realizar Pedido') }}
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>