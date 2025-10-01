<template>
  <Head title="Mis Pedidos" />
  
  <AuthenticatedLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Botones de Eliminación para Usuario -->
        <div class="mb-6 flex flex-wrap justify-center gap-3">
          <!-- Eliminar Seleccionados -->
          <button 
            v-if="selectedOrders.length >= 2" 
            @click="deleteSelectedOrders" 
            :disabled="deletingSelected"
            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>{{ deletingSelected ? 'Eliminando...' : `Eliminar Seleccionados (${selectedOrders.length})` }}</span>
          </button>

          <!-- Eliminar Todo -->
          <button 
            v-if="orders.length > 0" 
            @click="deleteAllOrders" 
            :disabled="deletingAll"
            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>{{ deletingAll ? 'Eliminando...' : 'Eliminar Todo' }}</span>
          </button>
        </div>

        <!-- Botón Deshacer Overlay -->
        <div v-if="showUndo && undoData" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50">
          <div class="bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-4 animate-pulse border-2 border-blue-400">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
            </svg>
            <div class="flex items-center space-x-3">
              <span class="font-medium">¿Deshacer eliminación? ({{ undoTimer }}s)</span>
              <button @click="undoLastAction" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded font-medium transition-colors">
                Deshacer
              </button>
              <button @click="cancelUndo" class="bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded font-medium transition-colors" title="Cancelar">
                ✕
              </button>
            </div>
          </div>
        </div>

        <!-- Lista de pedidos del usuario -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="w-12 px-4 py-3 text-center">
                      <input 
                        type="checkbox" 
                        :checked="selectedOrders.length === orders.length" 
                        @change="toggleSelectAll" 
                        class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500"
                      >
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Factura</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
                    <!-- Checkbox -->
                    <td class="px-4 py-4 text-center">
                      <input 
                        type="checkbox" 
                        :value="order.id" 
                        v-model="selectedOrders" 
                        class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500"
                      >
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ order.id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(order.created_at) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(order.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ getStatusText(order.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-amber-600">₲ {{ Number(order.total).toLocaleString('es-PY') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link
                        :href="`/orders/${order.id}/invoice`"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                      >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Ver Factura
                      </Link>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button @click="viewOrder(order)" class="text-amber-600 hover:text-amber-900">Ver Detalles</button>
                        <button 
                          @click="deleteOrder(order.id)" 
                          class="text-red-600 hover:text-red-900"
                          title="Eliminar pedido"
                        >
                          Eliminar
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Empty state -->
            <div v-if="orders.length === 0" class="text-center py-12">
              <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
              </div>
              <p class="text-gray-600">No hay pedidos registrados.</p>
            </div>
          </div>
        </div>

        <!-- Order Detail Modal - Mejorado -->
        <div v-if="selectedOrder" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-start justify-center p-4 z-50 overflow-y-auto">
          <div class="relative w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-hidden my-8 transition-all duration-300 transform hover:scale-[1.005]">
            <!-- Encabezado con gradiente -->
            <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white p-6 relative overflow-hidden">
              <!-- Elementos decorativos -->
              <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full -mr-10 -mt-10"></div>
              <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-500/5 rounded-full -ml-32 mb-10"></div>
              
              <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                  <div class="mb-4 md:mb-0">
                    <h2 class="text-2xl font-bold flex items-center">
                      <span class="mr-2">📦</span> Detalles del Pedido #{{ selectedOrder.id }}
                    </h2>
                    <p class="text-amber-100 text-sm mt-1">Cafetería Daylen • {{ formatDate(selectedOrder.created_at) }}</p>
                  </div>
                  
                  <div class="flex items-center space-x-3">
                    <Link 
                      :href="`/orders/${selectedOrder.id}/invoice`"
                      target="_blank"
                      class="group relative inline-flex items-center px-4 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-medium text-white transition-all duration-200 overflow-hidden"
                    >
                      <span class="relative z-10 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Ver Factura</span>
                      </span>
                      <span class="absolute inset-0 bg-white/10 group-hover:bg-white/20 transition-colors duration-300"></span>
                    </Link>
                    
                    <button 
                      @click="selectedOrder = null" 
                      class="p-2 text-amber-100 hover:text-white rounded-full hover:bg-white/10 transition-colors duration-200"
                      title="Cerrar"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Cuerpo del modal -->
            <div class="p-6 md:p-8">
              <!-- Sección de información -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Información del Pedido -->
                <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                  <div class="flex items-center mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                      <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                      </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Información del Pedido</h3>
                  </div>
                  <div class="space-y-2 text-sm text-gray-600 pl-12">
                    <div class="flex justify-between">
                      <span class="text-gray-600">Estado:</span>
                      <span :class="getStatusClass(selectedOrder.status) + ' px-2.5 py-0.5 rounded-full text-xs font-medium'">
                        {{ getStatusText(selectedOrder.status) }}
                      </span>
                    </div>
                    <div class="flex justify-between">
                      <span>Fecha:</span>
                      <span class="font-medium">{{ formatDate(selectedOrder.created_at) }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span>Cliente:</span>
                      <span class="font-medium">{{ selectedOrder.user?.name || 'Usuario eliminado' }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span>Email:</span>
                      <span class="font-medium">{{ selectedOrder.user?.email || 'N/A' }}</span>
                    </div>
                    <div v-if="selectedOrder.payment_id" class="flex justify-between">
                      <span>ID de Pago:</span>
                      <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">{{ selectedOrder.payment_id }}</span>
                    </div>
                  </div>
                </div>

                <!-- Resumen de Pago -->
                <div class="bg-amber-50 p-5 rounded-xl border border-amber-100">
                  <div class="flex items-center mb-3">
                    <div class="p-2 bg-amber-100 rounded-lg mr-3">
                      <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                      </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Resumen de Pago</h3>
                  </div>
                  <div class="space-y-2 text-sm text-gray-600 pl-12">
                    <div class="flex justify-between">
                      <span>Subtotal:</span>
                      <span>₲ {{ Number(selectedOrder.total).toLocaleString('es-PY') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                      <span>IVA (10% incluido):</span>
                      <span>Incluido en el precio</span>
                    </div>
                    <div class="border-t border-amber-200 my-2"></div>
                    <div class="flex justify-between font-bold text-lg">
                      <span>Total:</span>
                      <span class="text-amber-700">₲ {{ Number(selectedOrder.total).toLocaleString('es-PY') }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Lista de Productos -->
              <div class="mb-8">
                <div class="flex items-center mb-4">
                  <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                  </div>
                  <h3 class="text-base font-semibold text-gray-800">Productos del Pedido</h3>
                </div>
                
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unit.</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="item in (selectedOrder.order_items || selectedOrder.orderItems || [])" :key="item.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-800 font-medium text-sm mr-3">
                              {{ (item.product?.name || item.product_name || '??').substring(0, 2).toUpperCase() }}
                            </div>
                            <div>
                              <div class="text-sm font-medium text-gray-900">
                                {{ item.product?.name || item.product_name || 'Producto no disponible' }}
                                <span v-if="!item.product && item.product_id" class="text-red-500 text-xs">(eliminado)</span>
                              </div>
                              <div v-if="item.options && JSON.parse(item.options).length > 0" class="text-xs text-gray-500 mt-1">
                                <span v-for="(option, idx) in JSON.parse(item.options)" :key="idx" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                  {{ option.name }}
                                </span>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-800 font-medium">
                            {{ item.quantity }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-700">
                          ₲ {{ Number(item.price || item.product?.price || 0).toLocaleString('es-PY') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                          <span class="font-semibold">₲ {{ Number(item.subtotal || 0).toLocaleString('es-PY') }}</span>
                        </td>
                      </tr>
                      <!-- Mensaje si no hay productos -->
                      <tr v-if="(selectedOrder.order_items || selectedOrder.orderItems || []).length === 0">
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                          No hay productos en este pedido
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Notas adicionales -->
              <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-start">
                  <svg class="h-5 w-5 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <div>
                    <h4 class="text-sm font-medium text-gray-900">¿Necesitas ayuda con tu pedido?</h4>
                    <p class="text-sm text-gray-600 mt-1">Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar a nuestro equipo de soporte.</p>
                    <p class="text-sm text-amber-700 font-medium mt-2">Email: daylencoffee@gmail.com</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, defineProps } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  orders: {
    type: Array,
    default: () => []
  }
})

// Reactive data
const selectedOrder = ref(null)
const selectedOrders = ref([])
const showUndo = ref(false)
const undoData = ref(null)
const undoTimer = ref(5)
const undoTimeout = ref(null)
const deletingId = ref(null)
const deletingSelected = ref(false)
const deletingAll = ref(false)

const viewOrder = (order) => {
  selectedOrder.value = order
}

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

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const toggleSelectAll = () => {
  selectedOrders.value = selectedOrders.value.length === props.orders.length 
    ? [] 
    : props.orders.map(o => o.id)
}

const deleteOrder = async (orderId) => {
  if (!confirm('¿Estás seguro de que quieres eliminar este pedido?')) return
  
  deletingId.value = orderId
  try {
    const orderToDelete = props.orders.find(o => o.id === orderId)
    
    undoData.value = { 
      type: 'selected', 
      orders: [{
        ...orderToDelete,
        order_items: (orderToDelete.order_items || orderToDelete.orderItems || []).map(item => ({
            ...item,
            product_name: item.product?.name || item.product_name || 'Producto eliminado',
            product_id: item.product_id,
            price: item.price,
            quantity: item.quantity,
            subtotal: item.subtotal,
            size: item.size,
            sugar: item.sugar
        }))
      }]  
    }

    await router.delete(`/orders/${orderId}`, {
      onSuccess: () => { 
        startUndoTimer()
        router.reload({ only: ['orders'] }) 
      },
      onError: () => { 
        alert('Error al eliminar el pedido')
        undoData.value = null 
      }
    })
  } finally { 
    deletingId.value = null 
  }
}

const deleteSelectedOrders = async () => {
  if (selectedOrders.value.length === 0) return
  
  const confirmMessage = `¿Estás seguro de que quieres eliminar ${selectedOrders.value.length} pedido${selectedOrders.value.length !== 1 ? 's' : ''}?`
  if (!confirm(confirmMessage)) return

  deletingSelected.value = true
  try {
    const ordersToDelete = props.orders.filter(o => selectedOrders.value.includes(o.id))
    
    undoData.value = { 
      type: 'selected', 
      orders: ordersToDelete.map(order => ({
        ...order,
        order_items: (order.order_items || order.orderItems || []).map(item => ({
            ...item,
            product_name: item.product?.name || item.product_name || 'Producto eliminado',
            product_id: item.product_id,
            price: item.price,
            quantity: item.quantity,
            subtotal: item.subtotal,
            size: item.size,
            sugar: item.sugar
        }))
      }))
    }

    await router.post('/orders/bulk-delete', { 
      order_ids: selectedOrders.value 
    }, {
      onSuccess: () => { 
        startUndoTimer()
        selectedOrders.value = []
        router.reload({ only: ['orders'] }) 
      },
      onError: () => { 
        alert('Error al eliminar los pedidos seleccionados')
        undoData.value = null 
      }
    })
  } finally { 
    deletingSelected.value = false 
  }
}

const deleteAllOrders = async () => {
  if (!confirm('¿Estás COMPLETAMENTE SEGURO de que quieres eliminar TODOS tus pedidos?')) return

  deletingAll.value = true
  try {
    undoData.value = { 
      type: 'all', 
      orders: props.orders.map(order => ({
        ...order,
        order_items: (order.order_items || order.orderItems || []).map(item => ({
            ...item,
            product_name: item.product?.name || item.product_name || 'Producto eliminado',
            product_id: item.product_id,
            price: item.price,
            quantity: item.quantity,
            subtotal: item.subtotal,
            size: item.size,
            sugar: item.sugar
        }))
      }))
    }

    await router.post('/orders/delete-all', {}, {
      onSuccess: () => { 
        startUndoTimer()
        selectedOrders.value = []
        router.reload({ only: ['orders'] }) 
      },
      onError: () => { 
        alert('Error al eliminar todos los pedidos')
        undoData.value = null 
      }
    })
  } finally { 
    deletingAll.value = false 
  }
}

const undoLastAction = async () => {
  if (!undoData.value) return

  showUndo.value = false
  if (undoTimeout.value) {
    clearTimeout(undoTimeout.value)
    undoTimeout.value = null
  }

  try {
    await router.post('/orders/restore', { 
      undo_data: undoData.value 
    }, {
      onSuccess: () => { 
        router.reload({ only: ['orders'] })
        undoData.value = null 
      },
      onError: () => { 
        alert('Error al restaurar los pedidos') 
      }
    })
  } catch (err) {
    console.error('Error restaurando pedidos:', err)
  }
}

const startUndoTimer = () => {
  showUndo.value = true
  undoTimer.value = 5

  const countdown = () => {
    undoTimer.value--
    if (undoTimer.value > 0) {
      undoTimeout.value = setTimeout(countdown, 1000)
    } else {
      showUndo.value = false
      undoData.value = null
      undoTimeout.value = null
    }
  }
  undoTimeout.value = setTimeout(countdown, 1000)
}

const cancelUndo = () => {
  showUndo.value = false
  if (undoTimeout.value) {
    clearTimeout(undoTimeout.value)
    undoTimeout.value = null
  }
  undoData.value = null
}
</script>