@extends('inventory::layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">🧾 Product Inventory</h1>

    <v-product-list></v-product-list>
@endsection

@pushOnce('scripts')
<!-- Inline Vue template -->
<script type="text/x-template" id="v-product-list-template">
  <div>
    <table class="w-full border border-gray-300 rounded-lg">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 text-left">Name</th>
          <th class="text-center">Stock</th>
          <th class="text-center">Price</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="products.length === 0">
          <td colspan="3" class="text-center text-gray-500 p-3">No products found</td>
        </tr>
        <tr v-for="p in products" :key="p.id" class="border-b">
          <td class="p-2">@{{ p.name }}</td>
          <td class="text-center">@{{ p.stock }}</td>
          <td class="text-center">@{{ p.price }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</script>

<!-- Register component and mount Vue -->
<script type="module">
    // Register component
     window.app.component('v-product-list', {
        template: '#v-product-list-template',
        data() {
            return { products: [] };
        },
        methods: {
            fetchProducts() {
                fetch('/inventory/products/list')
                    .then(res => res.json())
                    .then(data => this.products = data ?? [])
                    .catch(() => this.products = []);
            }
        },
        mounted() {
            this.fetchProducts();
        }
    });

    // ✅ Mount Vue AFTER component registration
    // app.mount('#app');
</script>
@endPushOnce
