<template>
  <AuthenticatedLayout>
    <template #pageHeader>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Gestión de Stock
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Estadísticas de stock -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500">Productos en Stock</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ stockStats.inStock }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500">Stock Bajo</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ stockStats.lowStock }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500">Sin Stock</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ stockStats.outOfStock }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                  </svg>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500">Total Productos</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ stockStats.total }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Producto</label>
                <input v-model="searchQuery" type="text" placeholder="Nombre del producto..." class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado de Stock</label>
                <select v-model="stockFilter" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <option value="">Todos</option>
                  <option value="inStock">En Stock</option>
                  <option value="lowStock">Stock Bajo</option>
                  <option value="outOfStock">Sin Stock</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                <select v-model="categoryFilter" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <option value="">Todas las categorías</option>
                  <option value="bebidas_calientes">Bebidas Calientes</option>
                  <option value="bebidas_frias">Bebidas Frías</option>
                  <option value="tes_infusiones">Tés e Infusiones</option>
                  <option value="chocolates">Chocolates</option>
                  <option value="pasteleria">Pastelería</option>
                  <option value="tortas_postres">Tortas y Postres</option>
                  <option value="salados">Salados</option>
                  <option value="otros">Otros</option>
                </select>
              </div>
              <div class="flex items-end">
                <button @click="clearFilters" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-md">
                  Limpiar Filtros
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de productos -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
              <button @click="showBulkUpdateModal = true" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Actualización Masiva
              </button>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Actual</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <input type="checkbox" v-model="selectedProducts" :value="product.id" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="h-10 w-10 rounded-full object-cover">
                          <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                          <div class="text-sm text-gray-500">₲{{ product.price.toLocaleString() }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" :class="getCategoryColor(product.category)">
                        {{ getCategoryName(product.category) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <input v-model.number="product.stock" type="number" min="0" class="w-20 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 text-center">
                        <button @click="updateStock(product)" class="ml-2 text-amber-600 hover:text-amber-900">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                        </button>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" :class="getStockStatusColor(product.stock)">
                        {{ getStockStatus(product.stock) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button @click="showStockHistory(product)" class="text-blue-600 hover:text-blue-900">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                          </svg>
                        </button>
                        <button @click="restockProduct(product)" class="text-green-600 hover:text-green-900">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
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
      </div>
    </div>

    <!-- Modal de actualización masiva -->
    <div v-if="showBulkUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Actualización Masiva de Stock</h3>
            <button @click="showBulkUpdateModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Productos Seleccionados</label>
              <p class="text-sm text-gray-600">{{ selectedProducts.length }} productos seleccionados</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Acción</label>
              <select v-model="bulkAction" class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                <option value="set">Establecer stock</option>
                <option value="add">Agregar stock</option>
                <option value="subtract">Restar stock</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
              <input v-model.number="bulkQuantity" type="number" min="0" class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
            </div>
          </div>

          <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
            <button @click="showBulkUpdateModal = false" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button @click="executeBulkUpdate" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-md">
              Ejecutar Actualización
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  products: Array
})

const searchQuery = ref('')
const stockFilter = ref('')
const categoryFilter = ref('')
const selectedProducts = ref([])
const selectAll = ref(false)
const showBulkUpdateModal = ref(false)
const bulkAction = ref('set')
const bulkQuantity = ref(0)

const stockStats = computed(() => {
  const products = props.products || []
  return {
    total: products.length,
    inStock: products.filter(p => p.stock > 10).length,
    lowStock: products.filter(p => p.stock > 0 && p.stock <= 10).length,
    outOfStock: products.filter(p => p.stock === 0).length
  }
})

const filteredProducts = computed(() => {
  let filtered = props.products || []
  
  if (searchQuery.value) {
    filtered = filtered.filter(p => 
      p.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (stockFilter.value) {
    switch (stockFilter.value) {
      case 'inStock':
        filtered = filtered.filter(p => p.stock > 10)
        break
      case 'lowStock':
        filtered = filtered.filter(p => p.stock > 0 && p.stock <= 10)
        break
      case 'outOfStock':
        filtered = filtered.filter(p => p.stock === 0)
        break
    }
  }
  
  if (categoryFilter.value) {
    filtered = filtered.filter(p => p.category === categoryFilter.value)
  }
  
  return filtered
})

const getCategoryName = (category) => {
  const names = {
    'bebidas_calientes': 'Bebidas Calientes',
    'bebidas_frias': 'Bebidas Frías',
    'tes_infusiones': 'Tés e Infusiones',
    'chocolates': 'Chocolates',
    'pasteleria': 'Pastelería',
    'tortas_postres': 'Tortas y Postres',
    'salados': 'Salados',
    'otros': 'Otros'
  }
  return names[category] || category
}

const getCategoryColor = (category) => {
  const colors = {
    'bebidas_calientes': 'bg-red-100 text-red-800',
    'bebidas_frias': 'bg-blue-100 text-blue-800',
    'tes_infusiones': 'bg-green-100 text-green-800',
    'chocolates': 'bg-amber-100 text-amber-800',
    'pasteleria': 'bg-purple-100 text-purple-800',
    'tortas_postres': 'bg-amber-100 text-amber-800',
    'salados': 'bg-orange-100 text-orange-800',
    'otros': 'bg-gray-100 text-gray-800'
  }
  return colors[category] || 'bg-gray-100 text-gray-800'
}

const getStockStatus = (stock) => {
  if (stock === 0) return 'Sin Stock'
  if (stock <= 10) return 'Stock Bajo'
  return 'En Stock'
}

const getStockStatusColor = (stock) => {
  if (stock === 0) return 'bg-red-100 text-red-800'
  if (stock <= 10) return 'bg-amber-100 text-amber-800'
  return 'bg-green-100 text-green-800'
}

const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedProducts.value = filteredProducts.value.map(p => p.id)
  } else {
    selectedProducts.value = []
  }
}

const clearFilters = () => {
  searchQuery.value = ''
  stockFilter.value = ''
  categoryFilter.value = ''
  selectedProducts.value = []
  selectAll.value = false
}

const updateStock = (product) => {
  // Implementar actualización de stock
  console.log('Actualizar stock:', product)
}

const showStockHistory = (product) => {
  // Implementar historial de stock
  console.log('Historial de stock:', product)
}

const restockProduct = (product) => {
  // Implementar reabastecimiento
  console.log('Reabastecer producto:', product)
}

const executeBulkUpdate = () => {
  // Implementar actualización masiva
  console.log('Actualización masiva:', {
    products: selectedProducts.value,
    action: bulkAction.value,
    quantity: bulkQuantity.value
  })
  showBulkUpdateModal.value = false
}
</script>
