<template>  
  <AuthenticatedLayout v-if="$page.props.auth.user">
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Contenido para usuarios autenticados -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
          <div class="p-6 text-gray-900">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
              ☕ Bienvenido de vuelta
            </h1>
            <p class="text-lg text-gray-600 mb-6">
              ¡Qué bueno verte de nuevo, {{ $page.props.auth.user.name }}!
            </p>
            
            <!-- Contador del carrito -->
            <div class="flex items-center space-x-4">
              <a href="/cart" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                </svg>
                <span>Ver Carrito ({{ $page.props.cartCount || 0 }})</span>
              </a>
              <a href="/orders" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                </svg>
                <span>Mis Pedidos</span>
              </a>
            </div>

            <!-- Pedidos Recientes del Usuario -->
            <div class="mt-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                  <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Mis Últimos Pedidos</h2>
                    <a href="/orders" class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                      Ver todos →
                    </a>
                  </div>

                  <div v-if="recentOrders && recentOrders.length > 0" class="space-y-3">
                    <div v-for="order in recentOrders.slice(0, 2)" :key="order.id" class="p-4 border border-gray-200 rounded-lg">
                      <div class="flex justify-between items-start">
                        <div>
                          <h3 class="font-medium text-gray-900">Pedido #{{ order.id }}</h3>
                          <p class="text-sm text-gray-600">{{ formatDate(order.created_at) }}</p>
                          <p class="text-sm text-gray-600">{{ order.orderItems?.length || 0 }} producto{{ order.orderItems?.length !== 1 ? 's' : '' }}</p>
                        </div>
                        <div class="text-right">
                          <span :class="getOrderStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs font-medium">
                            {{ getOrderStatusText(order.status) }}
                          </span>
                          <p class="text-sm font-medium text-gray-900 mt-1">
                            ₲ {{ Number(order.total || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div v-else class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500">Aún no has hecho ningún pedido</p>
                    <a href="/home" class="text-amber-600 hover:text-amber-700 text-sm font-medium mt-2 inline-block">
                      ¡Haz tu primer pedido! →
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Lista de productos -->
        <ProductList 
          :products="$page.props.products"
          :promotional-products="$page.props.promotionalProducts"
          :categories="$page.props.categories"
          :current-category="$page.props.currentCategory"
          :current-search="$page.props.filters?.search || ''"
          :current-sort="$page.props.filters?.sort || 'name'"
          :currentSearch="$page.props.currentSearch"
          :currentSort="$page.props.currentSort"
        />
      </div>
    </div>
    <!-- Debug visual temporal -->
    <div v-if="$page.props.debug" class="fixed top-0 right-0 bg-blue-100 p-3 text-xs z-50 max-w-md">
        <div class="font-bold">🔍 DEBUG BACKEND</div>
        <div><strong>Parámetros recibidos:</strong></div>
        <div>Search: "{{ $page.props.debug.params.search_param }}"</div>
        <div>Sort: {{ $page.props.debug.params.sort_param }}</div>
        <div>Category: {{ $page.props.debug.params.category_param }}</div>
    
        <div class="mt-2"><strong>Valores procesados:</strong></div>
        <div>Search: "{{ $page.props.debug.processed.search_var }}"</div>
        <div>Sort: {{ $page.props.debug.processed.sort_var }}</div>
        <div>Category: {{ $page.props.debug.processed.category_var }}</div>
    
        <div class="mt-2"><strong>Resultados:</strong></div>
        <div>Productos: {{ $page.props.debug.results.products_count }}</div>
    </div>
  </AuthenticatedLayout>
  
  <GuestLayout v-else>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Mensaje de bienvenida -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
          <div class="p-6 text-gray-900">
            <h1 class="text-3xl font-bold text-amber-600 mb-4">
              ☕ Nuestra Cafetería
            </h1>
            <p class="text-lg text-gray-600 mb-6">
              Descubre nuestros deliciosos productos de café, pasteles y más.
            </p>
            
            <!-- Contador del carrito -->
            <div class="flex items-center space-x-4">
              <a href="/cart" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <span>Ver Carrito ({{ $page.props.cartCount || 0 }})</span>
              </a>
            </div>
          </div>
        </div>

        <!-- Lista de productos -->
        <ProductList 
          :products="$page.props.products"
          :promotional-products="$page.props.promotionalProducts"
          :categories="$page.props.categories"
          :current-category="$page.props.currentCategory"
          :current-search="$page.props.filters?.search || ''"
          :current-sort="$page.props.filters?.sort || 'name'"
          :currentSearch="$page.props.currentSearch"
          :currentSort="$page.props.currentSort"
        />
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProductList from '@/Components/ProductList.vue';
import PromotionalProducts from '@/Components/PromotionalProducts.vue';

const props = defineProps({
  products: {
    type: Array,
    required: true,
    default: () => []
  },
  promotionalProducts: {
    type: Array,
    default: () => []
  },
  categories: {
    type: Array,
    default: () => []
  },
  cartCount: {
    type: Number,
    default: 0,
  },
  recentOrders: {
    type: Array,
    default: () => []
  },
  currentCategory: {
    type: [String, Number],
    default: null
  },
  currentSearch: {
    type: String,
    default: ''
  },
  currentSort: {
    type: String,
    default: 'name'
  }
});

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('es-ES', {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getOrderStatusClass = (status) => {
  switch (status) {
    case 'pending': return 'bg-amber-100 text-amber-800';
    case 'paid': return 'bg-green-100 text-green-800';
    case 'cancelled': return 'bg-red-100 text-red-800';
    default: return 'bg-gray-100 text-gray-800';
  }
};

const getOrderStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente';
    case 'paid': return 'Pagado';
    case 'cancelled': return 'Cancelado';
    default: return status;
  }
};
</script>