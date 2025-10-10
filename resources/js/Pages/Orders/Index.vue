<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Mis Pedidos</h1>
          <p class="text-gray-600">Gestiona tus pedidos realizados</p>
        </div>

        <!-- Botones de acción -->
        <div class="mb-6 flex flex-wrap gap-3">
          <!-- Botón Deshacer -->
          <div v-if="showUndo && undoData" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50">
            <div class="bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-4 animate-pulse border-2 border-blue-400">
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
              </svg>
              <div class="flex items-center space-x-3">
                <span class="font-medium">¿Deshacer eliminación? ({{ undoTimer }}s)</span>
                <button
                  @click="undoLastAction"
                  class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded font-medium transition-colors"
                >
                  Deshacer
                </button>
                <button
                  @click="cancelUndo"
                  class="bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded font-medium transition-colors"
                  title="Cancelar"
                >
                  ✕
                </button>
              </div>
            </div>
          </div>

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

          <!-- Eliminar Todos Mis Pedidos -->
          <button
            v-if="orders.length > 0"
            @click="deleteAllOrders"
            :disabled="deletingAll"
            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>{{ deletingAll ? 'Eliminando...' : 'Eliminar Todos Mis Pedidos' }}</span>
          </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
          <p class="mt-2 text-gray-600">Cargando tus pedidos...</p>
        </div>

        <!-- Error -->
        <div v-else-if="error" class="text-center py-8">
          <div class="text-red-600 mb-4">
            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <p class="text-red-600 font-medium">{{ error }}</p>
          <button
            @click="loadOrders"
            class="mt-4 bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded"
          >
            Reintentar
          </button>
        </div>

        <!-- Tabla de pedidos -->
        <div v-else-if="orders.length > 0" class="bg-white shadow overflow-hidden sm:rounded-md">
          <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="relative w-12 px-6 sm:w-16 sm:px-8">
                      <input
                        type="checkbox"
                        :checked="selectedOrders.length === orders.length && orders.length > 0"
                        @change="toggleSelectAll"
                        class="w-4 h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 focus:ring-2"
                      />
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr
                    v-for="order in orders"
                    :key="order.id"
                    :class="[
                      'hover:bg-gray-50',
                      selectedOrders.includes(order.id) ? 'bg-amber-50 border-amber-200' : ''
                    ]"
                  >
                    <td class="relative px-6 sm:w-16 sm:px-8">
                      <input
                        type="checkbox"
                        :value="order.id"
                        v-model="selectedOrders"
                        class="w-4 h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 focus:ring-2"
                      />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      #{{ order.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ₲ {{ order.total.toLocaleString('es-PY') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="{
                          'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium': true,
                          'bg-yellow-100 text-yellow-800': order.status === 'pending',
                          'bg-green-100 text-green-800': order.status === 'paid',
                          'bg-red-100 text-red-800': order.status === 'cancelled'
                        }"
                      >
                        {{ order.status === 'pending' ? 'Pendiente' : order.status === 'paid' ? 'Pagado' : 'Cancelado' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ new Date(order.created_at).toLocaleDateString('es-ES') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button
                          @click="viewOrder(order)"
                          class="text-amber-600 hover:text-amber-900"
                          title="Ver detalles"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                          </svg>
                        </button>
                        <button
                          @click="deleteOrder(order.id)"
                          :disabled="deletingId === order.id"
                          class="text-red-600 hover:text-red-900 disabled:text-red-400"
                          title="Eliminar"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-else class="text-center py-12">
          <div class="text-gray-400 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <p class="text-gray-600">No tienes pedidos registrados.</p>
          <a
            href="/"
            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700"
          >
            Hacer un pedido
          </a>
        </div>

        <!-- Order Detail Modal -->
        <div v-if="selectedOrder" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
          <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">
                  Detalle del Pedido #{{ selectedOrder.id }}
                </h3>
                <button
                  @click="selectedOrder = null"
                  class="text-gray-400 hover:text-gray-600"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order info -->
                <div>
                  <h4 class="font-semibold text-gray-900 mb-4">Información del Pedido</h4>
                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span class="text-gray-600">Total:</span>
                      <span class="font-bold text-amber-600">₲ {{ selectedOrder.total.toLocaleString('es-PY') }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-600">Estado:</span>
                      <span :class="{
                        'px-2 py-1 rounded-full text-xs font-medium': true,
                        'bg-yellow-100 text-yellow-800': selectedOrder.status === 'pending',
                        'bg-green-100 text-green-800': selectedOrder.status === 'paid',
                        'bg-red-100 text-red-800': selectedOrder.status === 'cancelled'
                      }">
                        {{ selectedOrder.status === 'pending' ? 'Pendiente' : selectedOrder.status === 'paid' ? 'Pagado' : 'Cancelado' }}
                      </span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-600">Fecha:</span>
                      <span class="font-medium">{{ new Date(selectedOrder.created_at).toLocaleDateString('es-ES') }}</span>
                    </div>
                    <div v-if="selectedOrder.customer_name" class="flex justify-between">
                      <span class="text-gray-600">Nombre:</span>
                      <span class="font-medium">{{ selectedOrder.customer_name }}</span>
                    </div>
                    <div v-if="selectedOrder.phone" class="flex justify-between">
                      <span class="text-gray-600">Teléfono:</span>
                      <span class="font-medium">{{ selectedOrder.phone }}</span>
                    </div>
                    <div v-if="selectedOrder.address" class="flex justify-between">
                      <span class="text-gray-600">Dirección:</span>
                      <span class="font-medium">{{ selectedOrder.address }}</span>
                    </div>
                    <div v-if="selectedOrder.payment_method" class="flex justify-between">
                      <span class="text-gray-600">Método de pago:</span>
                      <span class="font-medium">{{ selectedOrder.payment_method === 'efectivo' ? 'Efectivo' : 'Tarjeta' }}</span>
                    </div>
                    <div v-if="selectedOrder.notes" class="flex justify-between">
                      <span class="text-gray-600">Notas:</span>
                      <span class="font-medium">{{ selectedOrder.notes }}</span>
                    </div>
                  </div>
                </div>

                <!-- Order items -->
                <div>
                  <h4 class="font-semibold text-gray-900 mb-4">Productos</h4>
                  <div class="space-y-2">
                    <div
                      v-for="item in selectedOrder.order_items"
                      :key="item.id"
                      class="flex justify-between items-center p-2 bg-gray-50 rounded"
                    >
                      <div>
                        <div class="font-medium">{{ item.product?.name || 'Producto eliminado' }}</div>
                        <div class="text-sm text-gray-500">Cantidad: {{ item.quantity }}</div>
                        <!-- AGREGAR PERSONALIZACIONES -->
                        <div v-if="item.size || item.sugar" class="text-xs text-gray-500 mt-1">
                          <span v-if="item.size">Tamaño: {{ item.size }}</span>
                          <span v-if="item.size && item.sugar"> • </span>
                          <span v-if="item.sugar">Azúcar: {{ item.sugar }}</span>
                        </div>
                        <!-- AGREGAR TOPPINGS Y ADDONS -->
                        <div v-if="(item.toppings && item.toppings.length > 0) || (item.addons && item.addons.length > 0)" class="text-xs text-gray-500 mt-1">
                          <div v-if="item.toppings && item.toppings.length > 0">
                            <span class="font-medium">Coberturas:</span> {{ item.toppings.join(', ') }}
                          </div>
                          <div v-if="item.addons && item.addons.length > 0">
                            <span class="font-medium">Agregados:</span> {{ item.addons.join(', ') }}
                          </div>
                        </div>
                      </div>
                      <div class="text-right">
                        <div class="font-semibold">₲ {{ item.subtotal.toLocaleString('es-PY') }}</div>
                        <div class="text-sm text-gray-500">₲ {{ item.product?.price ? item.product.price.toLocaleString('es-PY') : 'N/A' }} c/u</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, defineProps, watch, nextTick } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Props desde Inertia (OrderController@index)
const props = defineProps({
  orders: { type: Array, default: () => [] }
})

// Reactive data
const orders = ref(props.orders)
const loading = ref(false)
const error = ref('')
const selectedOrders = ref([])
const showUndo = ref(false)
const undoData = ref(null)
const undoTimer = ref(5)
const undoTimeout = ref(null)
const deletingId = ref(null)
const deletingSelected = ref(false)
const deletingAll = ref(false)
const selectedOrder = ref(null)

// Mantener orders sincronizado cuando Inertia actualiza las props
watch(() => props.orders, (val) => {
  orders.value = val || []
  selectedOrders.value = []

  // ✅ SOLO limpiar temporizador si NO hay deshacer activo
  if (!showUndo.value && !undoData.value) {
    if (undoTimeout.value) clearTimeout(undoTimeout.value)
    undoTimeout.value = null
    undoData.value = null
  }
})

// Methods
const loadOrders = async () => {
  loading.value = true
  error.value = ''

  try {
    await router.reload({ only: ['orders'] })
  } catch (err) {
    error.value = 'Error al cargar los pedidos'
    console.error('Error loading orders:', err)
  } finally {
    loading.value = false
  }
}

const toggleSelectAll = () => {
  if (selectedOrders.value.length === orders.value.length) {
    selectedOrders.value = []
  } else {
    selectedOrders.value = orders.value.map(o => o.id)
  }
}

const viewOrder = (order) => {
  selectedOrder.value = order
}

const deleteOrder = async (orderId) => {
  if (!confirm('¿Estás seguro de que quieres eliminar este pedido?')) return

  deletingId.value = orderId

  try {
    // Guardar datos para posible deshacer
    const orderToDelete = orders.value.find(o => o.id === orderId)
    undoData.value = {
      type: 'selected',
      orders: [orderToDelete],
      timestamp: Date.now()
    }

    await router.delete(`/orders/${orderId}`, {
      preserveScroll: true,
      onSuccess: () => {
        startUndoTimer()
        router.reload({ only: ['orders'] })
      },
      onError: () => {
        error.value = 'No se pudo eliminar el pedido'
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
    // Guardar datos para posible deshacer
    const ordersToDelete = orders.value.filter(o => selectedOrders.value.includes(o.id))
    undoData.value = {
      type: 'selected',
      orders: ordersToDelete,
      timestamp: Date.now()
    }

    await router.post('/orders/bulk-delete', {
      order_ids: selectedOrders.value
    }, {
      preserveScroll: true,
      onSuccess: () => {
        startUndoTimer()
        selectedOrders.value = []
        router.reload({ only: ['orders'] })
      },
      onError: (errors) => {
        error.value = 'No se pudieron eliminar algunos pedidos'
        undoData.value = null
        console.error('Error details:', errors)
      }
    })
  } finally {
    deletingSelected.value = false
  }
}

const deleteAllOrders = async () => {
  if (orders.value.length === 0) return

  const confirmMessage = `¿Estás COMPLETAMENTE SEGURO de que quieres eliminar TODOS tus pedidos (${orders.value.length})? Esta acción no se puede deshacer fácilmente.`
  if (!confirm(confirmMessage)) return

  deletingAll.value = true

  try {
    // Guardar datos para posible deshacer
    undoData.value = {
      type: 'all',
      orders: [...orders.value],
      timestamp: Date.now()
    }

    await router.post('/orders/delete-all', {}, {
      preserveScroll: true,
      onSuccess: () => {
        startUndoTimer()
        selectedOrders.value = []
        router.reload({ only: ['orders'] })
      },
      onError: (errors) => {
        error.value = 'No se pudieron eliminar todos los pedidos'
        undoData.value = null
        console.error('Error details:', errors)
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
    // Para usuarios, restaurar usando el controlador web
    await router.post('/orders/restore', {
      undo_data: undoData.value
    }, {
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ['orders'] })
        undoData.value = null
      },
      onError: () => {
        error.value = 'No se pudieron restaurar los pedidos'
      }
    })
  } catch (error) {
    console.error('Error al restaurar pedidos:', error)
  }
}

// ✅ CORREGIDO: startUndoTimer mejorado
const startUndoTimer = () => {
  showUndo.value = true
  undoTimer.value = 5

  // ✅ Asegurar que el estado sea visible inmediatamente
  nextTick(() => {
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
  })
}

// ✅ Nuevo método para cancelar deshacer manualmente
const cancelUndo = () => {
  showUndo.value = false
  if (undoTimeout.value) {
    clearTimeout(undoTimeout.value)
    undoTimeout.value = null
  }
  undoData.value = null
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  /*-webkit-line-clamp: 2;*/
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>