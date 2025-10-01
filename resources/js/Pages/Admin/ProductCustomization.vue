<template>
  <AuthenticatedLayout>
    <template #pageHeader>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Personalización de Productos
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filtros -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar Productos</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                <select v-model="filters.category" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
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
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tiene Tamaños</label>
                <select v-model="filters.has_size" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <option value="">Todos</option>
                  <option value="true">Sí</option>
                  <option value="false">No</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tiene Coberturas</label>
                <select v-model="filters.has_toppings" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <option value="">Todos</option>
                  <option value="true">Sí</option>
                  <option value="false">No</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tiene Agregados</label>
                <select v-model="filters.has_addons" class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <option value="">Todos</option>
                  <option value="true">Sí</option>
                  <option value="false">No</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Lista de productos -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
              <button @click="showAddProductModal = true" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Agregar Producto
              </button>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personalización</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
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
                      <div class="flex space-x-2">
                        <span v-if="product.has_size" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                          Tamaños
                        </span>
                        <span v-if="product.has_toppings" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                          Coberturas
                        </span>
                        <span v-if="product.has_addons" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                          Agregados
                        </span>
                        <span v-if="!product.has_size && !product.has_toppings && !product.has_addons" class="text-gray-500 text-xs">
                          Sin personalización
                        </span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-900">{{ product.stock }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button @click="editProduct(product)" class="text-rose-600 hover:text-rose-900">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                          </svg>
                        </button>
                        <button @click="manageCustomization(product)" class="text-blue-600 hover:text-blue-900">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
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

    <!-- Modal para gestionar personalización -->
    <div v-if="showCustomizationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Personalizar: {{ selectedProduct?.name }}</h3>
            <button @click="showCustomizationModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Tabs para diferentes tipos de personalización -->
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
              <button @click="activeTab = 'sizes'" :class="activeTab === 'sizes' ? 'border-rose-500 text-rose-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Tamaños
              </button>
              <button @click="activeTab = 'toppings'" :class="activeTab === 'toppings' ? 'border-rose-500 text-rose-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Coberturas
              </button>
              <button @click="activeTab = 'addons'" :class="activeTab === 'addons' ? 'border-rose-500 text-rose-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Agregados
              </button>
            </nav>
          </div>

          <!-- Contenido de tabs -->
          <div class="mt-6">
            <!-- Tamaños -->
            <div v-if="activeTab === 'sizes'">
              <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-medium text-gray-900">Tamaños disponibles</h4>
                <button @click="addSize" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold py-1 px-3 rounded">
                  + Agregar Tamaño
                </button>
              </div>
              <div class="space-y-2">
                <div v-for="(size, index) in customizationData.sizes" :key="index" class="flex items-center space-x-2">
                  <input v-model="size.size" placeholder="Tamaño" class="flex-1 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <input v-model.number="size.price" type="number" placeholder="Precio" class="w-24 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <button @click="removeSize(index)" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Coberturas -->
            <div v-if="activeTab === 'toppings'">
              <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-medium text-gray-900">Coberturas disponibles</h4>
                <button @click="addTopping" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold py-1 px-3 rounded">
                  + Agregar Cobertura
                </button>
              </div>
              <div class="space-y-2">
                <div v-for="(topping, index) in customizationData.toppings" :key="index" class="flex items-center space-x-2">
                  <input v-model="topping.name" placeholder="Nombre" class="flex-1 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <input v-model.number="topping.price" type="number" placeholder="Precio" class="w-24 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <button @click="removeTopping(index)" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Agregados -->
            <div v-if="activeTab === 'addons'">
              <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-medium text-gray-900">Agregados disponibles</h4>
                <button @click="addAddon" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold py-1 px-3 rounded">
                  + Agregar Agregado
                </button>
              </div>
              <div class="space-y-2">
                <div v-for="(addon, index) in customizationData.addons" :key="index" class="flex items-center space-x-2">
                  <input v-model="addon.name" placeholder="Nombre" class="flex-1 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <input v-model.number="addon.price" type="number" placeholder="Precio" class="w-24 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                  <label class="flex items-center">
                    <input v-model="addon.has_quantity" type="checkbox" class="mr-1">
                    <span class="text-sm text-gray-600">Cantidad</span>
                  </label>
                  <button @click="removeAddon(index)" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Botones de acción -->
          <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
            <button @click="showCustomizationModal = false" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button @click="saveCustomization" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-md">
              Guardar Cambios
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

const filters = reactive({
  category: '',
  has_size: '',
  has_toppings: '',
  has_addons: ''
})

const showCustomizationModal = ref(false)
const showAddProductModal = ref(false)
const selectedProduct = ref(null)
const activeTab = ref('sizes')

const customizationData = reactive({
  sizes: [],
  toppings: [],
  addons: []
})

const filteredProducts = computed(() => {
  let filtered = props.products || []
  
  if (filters.category) {
    filtered = filtered.filter(p => p.category === filters.category)
  }
  if (filters.has_size !== '') {
    filtered = filtered.filter(p => p.has_size === (filters.has_size === 'true'))
  }
  if (filters.has_toppings !== '') {
    filtered = filtered.filter(p => p.has_toppings === (filters.has_toppings === 'true'))
  }
  if (filters.has_addons !== '') {
    filtered = filtered.filter(p => p.has_addons === (filters.has_addons === 'true'))
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

const editProduct = (product) => {
  // Implementar edición de producto
  console.log('Editar producto:', product)
}

const manageCustomization = (product) => {
  selectedProduct.value = product
  showCustomizationModal.value = true
  
  // Cargar datos existentes
  customizationData.sizes = product.sizes || []
  customizationData.toppings = product.toppings || []
  customizationData.addons = product.addons || []
}

const addSize = () => {
  customizationData.sizes.push({ size: '', price: 0 })
}

const removeSize = (index) => {
  customizationData.sizes.splice(index, 1)
}

const addTopping = () => {
  customizationData.toppings.push({ name: '', price: 0 })
}

const removeTopping = (index) => {
  customizationData.toppings.splice(index, 1)
}

const addAddon = () => {
  customizationData.addons.push({ name: '', price: 0, has_quantity: false })
}

const removeAddon = (index) => {
  customizationData.addons.splice(index, 1)
}

const saveCustomization = () => {
  // Implementar guardado de personalización
  console.log('Guardar personalización:', customizationData)
  showCustomizationModal.value = false
}
</script>
