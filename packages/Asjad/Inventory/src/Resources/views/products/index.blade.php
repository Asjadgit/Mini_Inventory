@extends('inventory::layouts.app')

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                        <span class="mr-3">🧾</span>
                        Product Inventory
                    </h1>
                    <p class="text-gray-600 text-lg">Manage and track your product inventory in real-time</p>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-sm border border-blue-100 p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-md">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4 shadow-inner">
                        <span class="text-blue-600 text-xl">📦</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-products">-</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-sm border border-green-100 p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-md">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4 shadow-inner">
                        <span class="text-green-600 text-xl">🔄</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">In Stock</p>
                        <p class="text-2xl font-bold text-gray-900" id="in-stock">-</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-white to-orange-50 rounded-xl shadow-sm border border-orange-100 p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-md">
                <div class="flex items-center">
                    <div class="rounded-full bg-orange-100 p-3 mr-4 shadow-inner">
                        <span class="text-orange-600 text-xl">💰</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Value</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-value">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product List Component -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
            <v-product-list></v-product-list>
        </div>
    </div>
@endsection

@pushOnce('scripts')
    <!-- Inline Vue template -->
    <script type="text/x-template" id="v-product-list-template">
        <div>
            <!-- Table Header with Actions -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Product List</h3>
                        <p class="text-gray-500 text-sm mt-1">All your inventory items in one place</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            @click="fetchProducts"
                            :disabled="loading"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-sm"
                        >
                            <span v-if="loading" class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent mr-2"></span>
                            <span v-else class="mr-2">🔄</span>
                            Refresh
                        </button>

                        <button
                            @click="showaddModal"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-4 focus:ring-green-200 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-sm"
                        >
                            <span class="mr-2">➕</span>
                            Add Product
                        </button>
                    </div>
                </div>
            </div>
            <!-- Top Controls -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <!-- Show entries -->
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Show</span>
                        <select
                            v-model.number="pagination.per_page"
                            @change="fetchProducts(1)"
                            class="px-3 py-1 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
                        >
                            <option v-for="n in perPageOptions" :key="n" :value="n">@{{ n }}</option>
                        </select>
                        <span class="text-gray-600">entries</span>
                    </div>
                </div>

            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Product Name
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Stock Level
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Price
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="products.length === 0 && !loading">
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="text-gray-300 mb-4 text-6xl">📦</div>
                                    <p class="text-gray-500 text-lg font-semibold mb-2">No products found</p>
                                    <p class="text-gray-400 text-sm">Get started by adding your first product</p>
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="p in products"
                            :key="p.id"
                            class="group transition-all duration-300 hover:bg-gradient-to-r hover:from-blue-50 hover:to-white hover:shadow-sm"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-4 shadow-inner">
                                        <span class="text-blue-600 text-sm">📦</span>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-700 transition-colors duration-300">
                                        @{{ p.name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold shadow-sm transition-all duration-300 transform group-hover:scale-105"
                                      :class="getStockLevelClass(p.stock)">
                                    @{{ p.stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-semibold text-gray-900 bg-gradient-to-r from-gray-50 to-white px-3 py-1.5 rounded-lg shadow-inner">
                                    $@{{ formatPrice(p.price) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold shadow-sm transition-all duration-300 transform group-hover:scale-105"
                                    :class="getStatusClass(p.stock)"
                                >
                                    @{{ getStatusText(p.stock) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Bottom Pagination -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <!-- Showing info -->
                        <div class="text-gray-600 text-sm">
                            Showing @{{ pagination.from || 0 }} to @{{ pagination.to || 0 }} of @{{ pagination.total }} entries
                        </div>

                        <!-- Pagination Buttons -->
                        <div class="flex items-center space-x-1 mt-2 sm:mt-0">
                            <button
                                @click="fetchProducts(pagination.current_page - 1)"
                                :disabled="pagination.current_page === 1"
                                class="px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 disabled:opacity-50 transition-all"
                            >
                                Prev
                            </button>

                            <button
                                v-for="page in visiblePages()"
                                :key="page"
                                @click="fetchProducts(page)"
                                :class="pagination.current_page === page
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                class="px-3 py-1 rounded-lg transition-all"
                            >
                                @{{ page }}
                            </button>

                            <button
                                @click="fetchProducts(pagination.current_page + 1)"
                                :disabled="pagination.current_page === pagination.last_page"
                                class="px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 disabled:opacity-50 transition-all"
                            >
                                Next
                            </button>
                        </div>
                    </div>

            </div>

                <!-- Add Product Modal -->
                <div v-show="showModal">
                    <div class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50 p-4">
                        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 relative transform transition-all duration-300 scale-100">
                            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                                <h2 class="text-2xl font-bold text-gray-900">Add New Product</h2>
                                <button
                                    @click="closeModal"
                                    class="text-gray-400 hover:text-gray-600 text-2xl transition-colors duration-300 transform hover:scale-110 cursor-pointer"
                                >
                                    ×
                                </button>
                            </div>

                            <form @submit="FormSubmit">
                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Product Name</label>
                                        <input
                                            type="text"
                                            name="name"
                                            v-model.trim="formValues.name"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 bg-gray-50 focus:bg-white"
                                            placeholder="Enter product name"
                                            required
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Stock Quantity</label>
                                        <input
                                            type="number"
                                            min="0"
                                            name="stock"
                                            v-model.number="formValues.stock"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 bg-gray-50 focus:bg-white"
                                            placeholder="Enter stock quantity"
                                            required
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-3">Price ($)</label>
                                        <input
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            name="price"
                                            v-model.number="formValues.price"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 bg-gray-50 focus:bg-white"
                                            placeholder="Enter price"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                                    <button
                                        type="button"
                                        @click="closeModal"
                                        class="px-6 py-3 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 rounded-xl hover:from-gray-300 hover:to-gray-400 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-300 transform hover:scale-105 cursor-pointer font-semibold shadow-sm"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="modalLoading"
                                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200 transition-all duration-300 transform hover:scale-105 cursor-pointer font-semibold shadow-sm disabled:opacity-60 disabled:cursor-not-allowed"
                                    >
                                        <span v-if="modalLoading" class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent mr-2"></span>
                                        Save Product
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <!-- Toast Notification -->
            <div
                v-if="toast.show"
                :class="toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
                class="fixed top-6 right-1/2 transform translate-x-1/2 sm:right-6 sm:translate-x-0 text-white px-5 py-3 rounded-xl shadow-lg flex items-center space-x-2 animate-fadeIn z-[9999]">

                <span v-if="toast.type === 'success'">✅</span>
                <span v-else>❌</span>
                <span class="font-medium">@{{ toast.message }}</span>
            </div>


            <!-- Loading State -->
            <div v-if="loading" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600 mb-4 shadow-inner"></div>
                    <p class="text-gray-600 font-semibold">Loading products...</p>
                    <p class="text-gray-400 text-sm mt-1">Please wait while we fetch your inventory</p>
                </div>
            </div>
        </div>
    </script>

    <!-- Register component and mount Vue -->
    <script type="module">
        // Register component
        window.app.component('v-product-list', {
            template: '#v-product-list-template',
            data() {
                return {
                    products: [],
                    loading: false, // for table fetching
                    modalLoading: false, // for form submit only ✅
                    showModal: false,
                    formValues: {
                        name: '',
                        stock: '',
                        price: '',
                    },
                    toast: {
                        show: false,
                        message: '',
                        type: 'success'
                    },
                    pagination: {
                        current_page: 1,
                        last_page: 1,
                        per_page: 10,
                        total: 0,
                        from: 0,
                        to: 0
                    },
                    perPageOptions: [10, 20,50, 100],
                };
            },
            methods: {
                async fetchProducts(page = 1) {
                    if (this.showModal) return; // Don't refresh when modal is open
                    this.loading = true;
                    try {
                        const response = await fetch(
                            `/inventory/products/list?page=${page}&per_page=${this.pagination.per_page}`
                        );
                        const data = await response.json();
                        this.products = data.data ?? [];

                        this.pagination.current_page = data.current_page;
                        this.pagination.last_page = data.last_page;
                        this.pagination.total = data.total;
                        this.pagination.from = data.from;
                        this.pagination.to = data.to;
                        this.updateStats();
                    } catch (error) {
                        console.error('Failed to fetch products:', error);
                        this.products = [];
                    } finally {
                        this.loading = false;
                    }
                },
                getStockLevelClass(stock) {
                    if (stock === 0)
                        return 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-200';
                    if (stock < 10)
                        return 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-200';
                    return 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-200';
                },
                formatPrice(price) {
                    return parseFloat(price).toFixed(2);
                },
                getStatusClass(stock) {
                    if (stock === 0)
                        return 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-200';
                    if (stock < 5)
                        return 'bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800 border border-orange-200';
                    return 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-200';
                },
                getStatusText(stock) {
                    if (stock === 0) return 'Out of Stock';
                    if (stock < 10) return 'Low Stock';
                    return 'In Stock';
                },
                updateStats() {
                    const totalProducts = this.products.length;
                    const inStock = this.products.filter(p => p.stock > 0).length;
                    const totalValue = this.products.reduce((sum, p) => sum + (p.price * p.stock), 0);

                    document.getElementById('total-products').textContent = totalProducts;
                    document.getElementById('in-stock').textContent = inStock;
                    document.getElementById('total-value').textContent = '$' + totalValue.toFixed(2);
                },
                showaddModal() {
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                },
                resetForm() {
                    this.formValues.name = '';
                    this.formValues.stock = '';
                    this.formValues.price = '';
                },
                showToast(message, type = 'success') {
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.show = true;

                    // Auto-hide after 3 seconds
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                },
                visiblePages() {
                    const total = this.pagination.last_page;
                    const current = this.pagination.current_page;
                    const delta = 2;
                    const range = [];

                    for (let i = Math.max(1, current - delta); i <= Math.min(total, current + delta); i++) {
                        range.push(i);
                    }

                    if (range[0] > 1) range.unshift('...');
                    if (range[range.length - 1] < total) range.push('...');
                    return range;
                },

                async FormSubmit(event) {
                    event.preventDefault();
                    try {
                        this.modalLoading = true;

                        const response = await fetch('/inventory/products/store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify(this.formValues)
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            console.error('Error:', result);
                            alert(result.message || 'Failed to save product.');
                            return;
                        }

                        // ✅ Fetch latest products from server
                        await this.fetchProducts();

                        // Update stats
                        this.updateStats();

                        // Reset and close modal
                        this.resetForm();
                        // 👇 small delay before closing modal to avoid flash
                        setTimeout(() => this.closeModal(), 150);

                        // ✅ Success toast
                        this.showToast('Product added successfully! 🎉', 'success');
                    } catch (error) {
                        console.error('❌ Failed to submit form:', error);
                    } finally {
                        this.modalLoading = false;
                    }
                }
            },
            mounted() {
                this.fetchProducts();
            }
        });
    </script>

    <style>
        /* Enhanced custom styles */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Enhanced smooth transitions */
        button,
        a,
        input,
        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced custom scrollbar for table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: linear-gradient(to right, #f8fafc, #f1f5f9);
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: linear-gradient(to right, #cbd5e1, #94a3b8);
            border-radius: 10px;
            border: 2px solid #f1f5f9;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to right, #94a3b8, #64748b);
        }

        /* Backdrop blur support for older browsers */
        @supports not (backdrop-filter: blur(10px)) {
            .backdrop-blur-sm {
                background-color: rgba(0, 0, 0, 0.7);
            }
        }

        /* Custom animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes slideDownFade {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            10%,
            90% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        .animate-fadeIn {
            animation: slideDownFade 3s ease-in-out;
        }

        select {
            cursor: pointer;
        }

        button[disabled] {
            cursor: not-allowed;
        }
    </style>
@endPushOnce
