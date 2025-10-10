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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"></path>
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
                <svg class="w-6 h-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
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