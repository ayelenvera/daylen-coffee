<template>
  <AdminLayout>
    <div class="p-6">
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestión de Pedidos</h1>
        <p class="text-gray-600">Administra todos los pedidos del sistema</p>
      </div>

      <!-- Botones de acción -->
      <div class="mb-6 flex flex-wrap justify-center gap-3 mx-4">
        <!-- Botón Deshacer -->
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

        <!-- Eliminar Seleccionados -->
        <button v-if="selectedOrders.length >= 2" @click="deleteSelectedOrders" :disabled="deletingSelected" class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          <span>{{ deletingSelected ? 'Eliminando...' : `Eliminar Seleccionados (${selectedOrders.length})` }}</span>
        </button>

        <!-- Eliminar Todo -->
        <button v-if="orders.length > 0" @click="deleteAllOrders" :disabled="deletingAll" class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          <span>{{ deletingAll ? 'Eliminando...' : 'Eliminar Todo' }}</span>
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
        <p class="mt-2 text-gray-600">Cargando pedidos...</p>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="text-center py-8">
        <p class="text-red-600">{{ error }}</p>
        <button @click="loadOrders" class="mt-4 bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded">Reintentar</button>
      </div>

      <!-- Tabla de pedidos -->
      <div v-else-if="orders.length > 0" class="bg-white shadow rounded-lg mx-4">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="w-12 px-6 py-3">
                  <input type="checkbox" :checked="selectedOrders.length === orders.length" @change="toggleSelectAll" class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="order in orders" :key="order.id" :class="selectedOrders.includes(order.id) ? 'bg-amber-50' : 'hover:bg-gray-50'">
                <td class="px-6 py-4">
                  <input type="checkbox" :value="order.id" v-model="selectedOrders" class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ order.id }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ order.customer_name }}</div>
                  <div class="text-sm text-gray-500">{{ order.user?.email }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">₲ {{ formatNumber(order.total) }}</td>
                <td class="px-6 py-4">
                  <select
                    @change="updateOrderStatus(order.id, $event.target.value)"
                    :value="order.status"
                    class="text-xs border-gray-300 rounded px-2 py-1 focus:ring-amber-500 focus:border-amber-500"
                  >
                    <option value="pending">Pendiente</option>
                    <option value="paid">Pagado</option>
                    <option value="cancelled">Cancelado</option>
                  </select>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(order.created_at) }}</td>
                <td class="px-6 py-4 text-sm font-medium">
                  <div class="flex space-x-2">
                    <button @click="viewInvoice(order)" class="text-green-600 hover:text-green-900" title="Ver factura">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                    </button>
                    <button @click="deleteOrder(order.id)" :disabled="deletingId === order.id" class="text-red-600 hover:text-red-900 disabled:text-red-400" title="Eliminar">
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

      <!-- Empty state -->
      <div v-else class="text-center py-12">
        <div class="text-gray-400 mb-4">
          <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <p class="text-gray-600">No hay pedidos registrados.</p>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, defineProps, watch, nextTick } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ orders: { type: Array, default: () => [] } })

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

watch(() => props.orders, (val) => {
  orders.value = val || []
  selectedOrders.value = []
  if (!showUndo.value && !undoData.value) {
    if (undoTimeout.value) clearTimeout(undoTimeout.value)
    undoTimeout.value = null
    undoData.value = null
  }
})

const loadOrders = async () => {
  loading.value = true
  try {
    await router.reload({ only: ['orders'] })
  } catch (err) {
    error.value = 'Error al cargar pedidos'
  } finally {
    loading.value = false
  }
}

const toggleSelectAll = () => {
  selectedOrders.value = selectedOrders.value.length === orders.value.length ? [] : orders.value.map(o => o.id)
}

const formatNumber = (num) => {
  return Number(num || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 })
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

const viewInvoice = (order) => {
  window.open(`/orders/invoice/${order.id}`, '_blank')
}

const updateOrderStatus = async (orderId, newStatus) => {
  try {
    await router.patch(`/admin/orders/${orderId}/status`, {
      status: newStatus
    }, {
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ['orders'] })
      },
      onError: () => {
        error.value = 'Error al actualizar el estado del pedido'
      }
    })
  } catch (error) {
    console.error('Error updating order status:', error)
  }
}

const deleteOrder = async (orderId) => {
  if (!confirm('¿Estás seguro de que quieres eliminar este pedido?')) return
  deletingId.value = orderId
  try {
    const orderToDelete = orders.value.find(o => o.id === orderId)
    undoData.value = { type: 'selected', orders: [orderToDelete] }
    await router.delete(`/admin/orders/${orderId}`, {
      onSuccess: () => { startUndoTimer(); router.reload({ only: ['orders'] }) },
      onError: () => { error.value = 'Error al eliminar'; undoData.value = null }
    })
  } finally { deletingId.value = null }
}

const deleteSelectedOrders = async () => {
  if (selectedOrders.value.length === 0) return
  if (!confirm(`¿Estás seguro de que quieres eliminar ${selectedOrders.value.length} pedido${selectedOrders.value.length !== 1 ? 's' : ''}?`)) return
  deletingSelected.value = true
  try {
    const ordersToDelete = orders.value.filter(o => selectedOrders.value.includes(o.id))
    undoData.value = { type: 'selected', orders: ordersToDelete }
    await router.post('/admin/orders/bulk-delete', { order_ids: selectedOrders.value }, {
      onSuccess: () => { startUndoTimer(); selectedOrders.value = []; router.reload({ only: ['orders'] }) },
      onError: (errors) => { error.value = 'Error al eliminar'; undoData.value = null }
    })
  } finally { deletingSelected.value = false }
}

const deleteAllOrders = async () => {
  if (!confirm('¿Estás COMPLETAMENTE SEGURO de que quieres eliminar TODOS los pedidos?')) return
  deletingAll.value = true
  try {
    undoData.value = { type: 'all', orders: [...orders.value] }
    await router.post('/admin/orders/delete-all', {}, {
      onSuccess: () => { startUndoTimer(); selectedOrders.value = []; router.reload({ only: ['orders'] }) },
      onError: (errors) => { error.value = 'Error al eliminar'; undoData.value = null }
    })
  } finally { deletingAll.value = false }
}

const undoLastAction = async () => {
  if (!undoData.value) return
  showUndo.value = false
  if (undoTimeout.value) clearTimeout(undoTimeout.value)
  try {
    await router.post('/admin/orders/restore', { undo_data: undoData.value }, {
      onSuccess: () => { router.reload({ only: ['orders'] }); undoData.value = null },
      onError: () => { error.value = 'Error al restaurar' }
    })
  } catch (error) { console.error('Error restaurando:', error) }
}

const startUndoTimer = () => {
  showUndo.value = true
  undoTimer.value = 5
  nextTick(() => {
    const countdown = () => {
      undoTimer.value--
      if (undoTimer.value > 0) undoTimeout.value = setTimeout(countdown, 1000)
      else { showUndo.value = false; undoData.value = null; undoTimeout.value = null }
    }
    undoTimeout.value = setTimeout(countdown, 1000)
  })
}

const cancelUndo = () => {
  showUndo.value = false
  if (undoTimeout.value) clearTimeout(undoTimeout.value)
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
