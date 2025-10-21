@extends('inventory::layouts.app')

@section('content')
<div class="w-full mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">🧾 Product Inventory</h1>
        <p class="text-gray-600">Manage and track your product inventory in real-time</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-3 mr-4">
                    <span class="text-blue-600 text-xl">📦</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-products">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <span class="text-green-600 text-xl">🔄</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">In Stock</p>
                    <p class="text-2xl font-bold text-gray-900" id="in-stock">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="rounded-full bg-orange-100 p-3 mr-4">
                    <span class="text-orange-600 text-xl">💰</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Value</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-value">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Product List Component -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <v-product-list></v-product-list>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="hidden text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500 mb-4"></div>
        <p class="text-gray-600">Loading products...</p>
    </div>
</div>
@endsection

@pushOnce('scripts')
<!-- Inline Vue template -->
<script type="text/x-template" id="v-product-list-template">
  <div>
    <!-- Table Header with Refresh Button -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">Product List</h3>
      <button
        @click="fetchProducts"
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
        :disabled="loading"
      >
        <span v-if="loading" class="inline-block animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mr-2"></span>
        <span v-else class="mr-2">🔄</span>
        Refresh
      </button>

      <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
        Add Product
      </button>
    </div>

    <!-- Products Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Level</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="products.length === 0 && !loading">
            <td colspan="4" class="px-6 py-8 text-center">
              <div class="text-gray-400 mb-2 text-4xl">📦</div>
              <p class="text-gray-500 text-lg font-medium">No products found</p>
              <p class="text-gray-400 text-sm mt-1">Add some products to get started</p>
            </td>
          </tr>
          <tr v-for="p in products" :key="p.id" class="hover:bg-gray-50 transition-colors duration-150">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">@{{ p.name }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                    :class="getStockLevelClass(p.stock)">
                @{{ p.stock }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
              $@{{ formatPrice(p.price) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                    :class="getStatusClass(p.stock)">
                @{{ getStatusText(p.stock) }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="px-6 py-8 text-center">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500 mb-4"></div>
      <p class="text-gray-600">Loading products...</p>
    </div>
  </div>
</script>

<!-- Register component and mount Vue -->
<script type="module">
    // Register component with enhanced functionality
    window.app.component('v-product-list', {
        template: '#v-product-list-template',
        data() {
            return {
                products: [],
                loading: false
            };
        },
        methods: {
            async fetchProducts() {
                this.loading = true;
                try {
                    const response = await fetch('/inventory/products/list');
                    const data = await response.json();
                    this.products = data ?? [];
                    this.updateStats();
                } catch (error) {
                    console.error('Failed to fetch products:', error);
                    this.products = [];
                } finally {
                    this.loading = false;
                }
            },
            getStockLevelClass(stock) {
                if (stock === 0) return 'bg-red-100 text-red-800';
                if (stock < 10) return 'bg-yellow-100 text-yellow-800';
                return 'bg-green-100 text-green-800';
            },
            getStatusClass(stock) {
                if (stock === 0) return 'bg-red-100 text-red-800';
                if (stock < 5) return 'bg-orange-100 text-orange-800';
                return 'bg-green-100 text-green-800';
            },
            getStatusText(stock) {
                if (stock === 0) return 'Out of Stock';
                if (stock < 5) return 'Low Stock';
                return 'In Stock';
            },
            formatPrice(price) {
                return parseFloat(price).toFixed(2);
            },
            updateStats() {
                // Update the stats cards
                const totalProducts = this.products.length;
                const inStock = this.products.filter(p => p.stock > 0).length;
                const totalValue = this.products.reduce((sum, p) => sum + (p.price * p.stock), 0);

                document.getElementById('total-products').textContent = totalProducts;
                document.getElementById('in-stock').textContent = inStock;
                document.getElementById('total-value').textContent = '$' + totalValue.toFixed(2);
            }
        },
        mounted() {
            this.fetchProducts();
        }
    });

    // ✅ Mount Vue AFTER component registration
    // app.mount('#app');
</script>

<style>
/* Additional custom styles */
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

/* Smooth transitions for interactive elements */
button, a {
    transition: all 0.2s ease-in-out;
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endPushOnce
