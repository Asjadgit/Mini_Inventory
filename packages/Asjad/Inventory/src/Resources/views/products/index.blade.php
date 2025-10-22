@extends('inventory::layouts.app')

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Professional Page Header -->
        <div class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <div class="flex items-center mb-3">
                        <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-2xl shadow-lg mr-5">
                            <span class="text-white text-2xl">📦</span>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Product Inventory</h1>
                            <p class="text-gray-600 text-lg mt-2">Manage and track your product inventory in real-time</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-7 transform transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 p-4 mr-5 shadow-sm">
                        <span class="text-blue-600 text-2xl">📦</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2 tracking-wide">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900 tracking-tight" id="total-products">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-7 transform transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="rounded-xl bg-gradient-to-br from-green-50 to-green-100 p-4 mr-5 shadow-sm">
                        <span class="text-green-600 text-2xl">🔄</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2 tracking-wide">In Stock</p>
                        <p class="text-2xl font-bold text-gray-900 tracking-tight" id="in-stock">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-7 transform transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 p-4 mr-5 shadow-sm">
                        <span class="text-amber-600 text-2xl">💰</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2 tracking-wide">Total Value</p>
                        <p class="text-2xl font-bold text-gray-900 tracking-tight" id="total-value">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Product List Component -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <v-product-list></v-product-list>
        </div>
    </div>
@endsection

@pushOnce('scripts')
    <!-- Inline Vue template -->
    <script type="text/x-template" id="v-product-list-template">
        <div>
            <!-- Professional Table Header with Actions -->
            <div class="px-8 py-6 border-b border-gray-200 bg-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 tracking-tight">Product List</h3>
                        <p class="text-gray-500 text-sm mt-2">All your inventory items in one place</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            @click="fetchProducts"
                            :disabled="loading"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-sm"
                        >
                            <span v-if="loading" class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-gray-500 border-t-transparent mr-2"></span>
                            <span v-else class="mr-2">🔄</span>
                            Refresh
                        </button>

                        <button
                            @click="showaddModal"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] shadow-sm"
                        >
                            <span class="mr-2">➕</span>
                            Add Product
                        </button>
                    </div>
                </div>
            </div>

            <!-- Professional Top Controls -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 border-b border-gray-200 bg-gray-50">
                <!-- Show entries -->
                <div class="flex items-center space-x-2">
                    <span class="text-gray-600 text-sm font-medium">Show</span>
                    <select
                        v-model.number="pagination.per_page"
                        @change="fetchProducts(1)"
                        class="px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    >
                        <option v-for="n in perPageOptions" :key="n" :value="n">@{{ n }}</option>
                    </select>
                    <span class="text-gray-600 text-sm font-medium">entries</span>
                </div>
            </div>

            <!-- Professional Products Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                                Product Name
                            </th>
                            <th class="px-8 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                                Stock Level
                            </th>
                            <th class="px-8 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                                Price
                            </th>
                            <th class="px-8 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                                Status
                            </th>
                            <th class="px-8 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="products.length === 0 && !loading">
                            <td colspan="5" class="px-8 py-16 text-center">
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
                            class="group transition-all duration-200 hover:bg-blue-50"
                        >
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-4 shadow-sm">
                                        <span class="text-blue-600 text-sm">📦</span>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-700 transition-colors duration-200">
                                        @{{ p.name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 transform group-hover:scale-105"
                                      :class="getStockLevelClass(p.stock)">
                                    @{{ p.stock }}
                                </span>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-semibold text-gray-900 px-3 py-1.5 rounded-lg">
                                    $@{{ formatPrice(p.price) }}
                                </span>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 transform group-hover:scale-105"
                                    :class="getStatusClass(p.stock)"
                                >
                                    @{{ getStatusText(p.stock) }}
                                </span>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button
                                        @click="showaddModal(p)"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 text-xs font-medium"
                                    >
                                        <span class="mr-1">✏️</span>
                                        Edit
                                    </button>
                                    <button
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105 text-xs font-medium"
                                        @click="deleteProduct(p.id)"
                                    >
                                        <span class="mr-1">🗑️</span>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Professional Bottom Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 border-t border-gray-200 bg-gray-50">
                    <!-- Showing info -->
                    <div class="text-gray-600 text-sm font-medium">
                        Showing @{{ pagination.from || 0 }} to @{{ pagination.to || 0 }} of @{{ pagination.total }} entries
                    </div>

                    <!-- Pagination Buttons -->
                    <div class="flex items-center space-x-1 mt-2 sm:mt-0">
                        <button
                            @click="fetchProducts(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1"
                            class="px-3 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 transition-all duration-200 text-sm font-medium"
                        >
                            Previous
                        </button>

                        <button
                            v-for="page in visiblePages()"
                            :key="page"
                            @click="fetchProducts(page)"
                            :class="pagination.current_page === page
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                            class="px-3 py-2 rounded-lg border transition-all duration-200 text-sm font-medium min-w-[40px]"
                        >
                            @{{ page }}
                        </button>

                        <button
                            @click="fetchProducts(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page"
                            class="px-3 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-50 transition-all duration-200 text-sm font-medium"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>

            <!-- Professional Add/Edit Product Modal -->
            <div v-if="showModal">
                <div class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50 p-4">
                    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 relative transform transition-all duration-200 scale-100">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">@{{ mode === 'create' || formValues.id ? 'Edit Product' : 'Add New Product' }}</h2>
                            <button
                                @click="closeModal"
                                class="text-gray-400 hover:text-gray-600 text-2xl transition-colors duration-200 transform hover:scale-110 cursor-pointer"
                            >
                                ×
                            </button>
                        </div>

                        <form @submit="FormSubmit">
                            <div class="space-y-5">
                                <div>
                                    <input
                                        type="hidden"
                                        name="id"
                                        v-model.trim="formValues.id"
                                    >
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        v-model.trim="formValues.name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                        placeholder="Enter product name"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Quantity</label>
                                    <input
                                        type="number"
                                        min="0"
                                        name="stock"
                                        v-model.number="formValues.stock"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                        placeholder="Enter stock quantity"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price ($)</label>
                                    <input
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        name="price"
                                        v-model.number="formValues.price"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                        placeholder="Enter price"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 transform hover:scale-105 cursor-pointer font-semibold"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    :disabled="modalLoading"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 cursor-pointer font-semibold disabled:opacity-60 disabled:cursor-not-allowed"
                                >
                                    <span v-if="modalLoading" class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent mr-2"></span>
                                    @{{ mode === 'create' ? 'Save Product' : 'Update Product' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Professional Toast Notification -->
            <div
                v-if="toast.show"
                :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
                class="fixed top-6 right-1/2 transform translate-x-1/2 sm:right-6 sm:translate-x-0 text-white px-5 py-3 rounded-lg shadow-lg flex items-center space-x-2 animate-fadeIn z-[9999] font-medium">

                <span v-if="toast.type === 'success'">✅</span>
                <span v-else>❌</span>
                <span>@{{ toast.message }}</span>
            </div>

            <!-- Professional Loading State -->
            <div v-if="loading" class="px-8 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600 mb-4"></div>
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
                    mode: 'create', // 'create' or 'edit'
                    formValues: {
                        id: '',
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
                    perPageOptions: [10, 20, 50, 100],
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
                showaddModal(product = null) {
                    if (product) {
                        // Edit mode
                        this.mode = 'edit';
                        this.formValues.id = product.id;
                        this.formValues.name = product.name;
                        this.formValues.stock = product.stock;
                        this.formValues.price = product.price;
                    } else {
                        // Create mode
                        this.mode = 'create';
                        this.resetForm();
                    }
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                },
                resetForm() {
                    this.formValues.id = null; // add this
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

                        const isCreate = this.mode === 'create' || !this.formValues.id;
                        const url = isCreate ?
                            '/inventory/products/store' :
                            `/inventory/update/product/${this.formValues.id}`;
                        const method = isCreate ? 'POST' : 'PUT';


                        const response = await fetch(url, {
                            method,
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

                        // Refresh products
                        if (this.mode === 'create') {
                            // Add new product to top of array
                            this.products.unshift(result.product); // use result.product
                        } else {
                            // Find the product and update in-place
                            const index = this.products.findIndex(p => p.id === this.formValues.id);
                            if (index !== -1) {
                                this.products[index] = result.product; // use result.product
                            }
                        }

                        this.updateStats();
                        this.resetForm();
                        setTimeout(() => this.closeModal(), 150);
                        this.showToast(
                            `Product ${this.mode === 'create' ? 'added' : 'updated'} successfully! 🎉`,
                            'success');

                    } catch (error) {
                        console.error('❌ Failed to submit form:', error);
                    } finally {
                        this.modalLoading = false;
                    }
                },
                async deleteProduct(id) {
                    if (!confirm('Are you sure to delete that record ?')) return;
                    try {
                        const response = await fetch(`/inventory/products/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        });

                        if (!response.ok) throw new Error("Failed to delete");

                        // Remove from local products list for instant UI update
                        this.products = this.products.filter(p => p.id !== id);
                        this.updateStats();
                        this.showToast('Product deleted successfully!', 'success');
                    } catch (err) {
                        console.error(err);
                        this.showToast('Failed to delete product', 'error');
                    }
                },
            },
            mounted() {
                this.fetchProducts();
            }
        });
    </script>

    <style>
        /* Professional custom styles */
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

        /* Professional smooth transitions */
        button,
        a,
        input,
        .transition-all {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Professional custom scrollbar for table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Backdrop blur support for older browsers */
        @supports not (backdrop-filter: blur(10px)) {
            .backdrop-blur-sm {
                background-color: rgba(0, 0, 0, 0.7);
            }
        }

        /* Professional animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes slideDownFade {
            0% {
                opacity: 0;
                transform: translateY(-16px);
            }

            10%,
            90% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(-16px);
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
