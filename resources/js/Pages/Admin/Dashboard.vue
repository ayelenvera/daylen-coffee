<template>
  <AdminLayout>
    <title>Estadísticas</title>
    
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center">
              <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total de Pedidos</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.total_orders || 0 }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center">
              <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total de Clientes</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.total_customers || 0 }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center">
              <div class="p-3 bg-amber-100 rounded-full">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total de Productos</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.total_products || 0 }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
              <a 
                href="/admin/products" 
                class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-md transition-colors"
              >
                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Gestionar Productos</span>
              </a>
              
              <a 
                href="/admin/orders" 
                class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-md transition-colors"
              >
                <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Gestionar Pedidos</span>
              </a>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-medium text-gray-900">Pedidos Recientes</h3>
              <div v-if="newOrdersCount > 0" class="flex items-center">
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                  +{{ newOrdersCount }} nuevo{{ newOrdersCount !== 1 ? 's' : '' }}
                </span>
              </div>
            </div>
            <div class="space-y-3">
              <div v-for="order in recentOrders" :key="order.id" class="p-3 bg-gray-50 rounded-md">
                <div class="flex justify-between items-center">
                  <span class="font-medium">Pedido #{{ order.id }}</span>
                  <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs">
                    {{ getStatusText(order.status) }}
                  </span>
                </div>
                <p class="text-sm text-gray-600">Cliente: {{ order.user?.name }}</p>
                <p class="text-sm text-gray-600">Total: ₲ {{ Number(order.total || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</p>
              </div>
              
              <div v-if="recentOrders.length === 0" class="text-center py-4">
                <p class="text-gray-500">No hay pedidos recientes</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({ total_orders: 0, total_customers: 0, total_products: 0, revenue: 0 })
  },
  recentOrders: {
    type: Array,
    default: () => []
  },
  newOrdersCount: {
    type: Number,
    default: 0
  }
})

const stats = props.stats
const recentOrders = props.recentOrders

const getStatusClass = (status) => {
  switch (status) {
    case 'pending': return 'bg-amber-100 text-amber-800'
    case 'paid': return 'bg-green-100 text-green-800'
    case 'cancelled': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}

const getStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente'
    case 'paid': return 'Pagado'
    case 'cancelled': return 'Cancelado'
    default: return status
  }
}
</script>