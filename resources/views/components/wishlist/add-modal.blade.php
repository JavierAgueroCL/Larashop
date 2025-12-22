<div x-data="{ 
    open: false, 
    productId: null, 
    wishlists: [], 
    loading: false,
    newListName: '',
    selectedListId: null,
    
    init() {
        this.$watch('open', value => {
            if (value && this.wishlists.length === 0) {
                this.fetchWishlists();
            }
        });
    },

    fetchWishlists() {
        this.loading = true;
        fetch('{{ route('wishlist.json') }}')
            .then(r => {
                if(r.status === 401) {
                    window.location.href = '{{ route('login') }}';
                    return [];
                }
                return r.json();
            })
            .then(data => {
                this.wishlists = data;
                // Select default list if exists
                const defaultList = data.find(l => l.is_default);
                if (defaultList) this.selectedListId = defaultList.id;
                else if (data.length > 0) this.selectedListId = data[0].id;
            })
            .finally(() => this.loading = false);
    },

    addToSelected() {
        if (!this.selectedListId) return;

        fetch(`/wishlist/${this.selectedListId}/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: this.productId })
        })
        .then(r => {
            if(r.status === 401) {
                window.location.href = '{{ route('login') }}';
                return;
            }
            return r.json();
        })
        .then(data => {
            if(!data) return;
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'top-end'
            });
            this.open = false;
        })
        .catch(e => {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error adding to wishlist'
            });
        });
    },

    createAndAdd() {
        if (!this.newListName) return;

        // First create list
        fetch('{{ route('wishlist.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: this.newListName })
        })
        .then(r => {
            if(r.status === 401) {
                window.location.href = '{{ route('login') }}';
                return;
            }
            if (r.ok) {
                // Refresh lists and select the new one (logic simplified: just refetch for now)
                this.newListName = '';
                this.fetchWishlists(); // Ideally we should get the ID back from store response to select it immediately
                // For now, let's just refresh and let user select
            }
        });
    }
}"
@open-add-to-wishlist.window="open = true; productId = $event.detail.productId"
x-show="open"
style="display: none;"
class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:p-12"
x-cloak>

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-md transition-opacity" @click="open = false" x-transition.opacity></div>

    <!-- Modal Content -->
    <div class="relative w-full bg-white rounded-lg shadow-xl overflow-hidden transform transition-all" style="max-width: 300px;" x-transition.scale>
        
        <!-- Header -->
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-base font-medium text-gray-900">{{ __('Add to Wishlist') }}</h3>
            <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-4">
            <div x-show="loading" class="flex justify-center py-4">
                <svg class="animate-spin h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div x-show="!loading">
                <p class="text-xs text-gray-500 mb-3">{{ __('Select a wishlist:') }}</p>
                
                <!-- List Selection -->
                <div class="space-y-2 mb-4 max-h-48 overflow-y-auto">
                    <template x-for="list in wishlists" :key="list.id">
                        <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50 cursor-pointer border border-transparent hover:border-gray-200">
                            <input type="radio" name="wishlist" :value="list.id" x-model="selectedListId" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300">
                            <span class="text-sm text-gray-900 truncate" x-text="list.name"></span>
                            <span x-show="list.is_default" class="text-xs text-gray-400 ml-1">({{ __('Default') }})</span>
                        </label>
                    </template>
                </div>

                <!-- Create New -->
                <div class="border-t border-gray-100 pt-3">
                    <div x-data="{ showNew: false }">
                        <button @click="showNew = !showNew" type="button" class="text-xs text-primary-600 hover:text-primary-700 font-medium flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            {{ __('Create new wishlist') }}
                        </button>
                        
                        <div x-show="showNew" class="mt-2 flex flex-col gap-2">
                            <input type="text" x-model="newListName" placeholder="Name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-xs">
                            <button @click="createAndAdd" type="button" class="w-full px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-xs font-medium">
                                {{ __('Create') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 flex flex-col gap-2">
            <button @click="addToSelected" :disabled="!selectedListId" class="w-full px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                {{ __('Accept') }}
            </button>
            <button @click="open = false" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                {{ __('Cancel') }}
            </button>
        </div>
    </div>
</div>
