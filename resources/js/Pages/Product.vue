<template>
  <Head :title="`${product?.name || 'Producto'} - Cafetería`" />
  
  <GuestLayout>
    <template #header>
      <div class="flex items-center space-x-4">
        <Link 
          :href="route('home')" 
          class="text-amber-600 hover:text-amber-700 flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          <span>Volver</span>
        </Link>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ product?.name || 'Cargando...' }}
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Loading state -->
        <div v-if="loading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
          <p class="mt-2 text-gray-600">Cargando producto...</p>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="text-center py-8">
          <div class="text-red-600 mb-4">
            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <p class="text-red-600 font-medium">{{ error }}</p>
          <div class="mt-4 space-x-4">
            <button 
              @click="loadProduct" 
              class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded"
            >
              Reintentar
            </button>
            <Link 
              :href="route('home')" 
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
              Volver al Inicio
            </Link>
          </div>
        </div>

        <!-- Product detail -->
        <div v-else-if="product" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- Product image -->
              <div class="aspect-w-16 aspect-h-9">
                <img 
                  v-if="product.image" 
                  :src="product.image" 
                  :alt="product.name"
                  class="w-full h-96 object-cover rounded-lg shadow-md"
                />
                <div v-else class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                  <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </div>
              </div>

              <!-- Product info -->
              <div class="space-y-6">
                <div>
                  <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
                  
                  <div class="flex items-center space-x-4 mb-4">
                    <span class="text-4xl font-bold text-amber-600">₲ {{ Number(product.price || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                    <div class="flex items-center space-x-2">
                      <span class="text-sm text-gray-500">Stock:</span>
                      <span 
                        :class="product.stock > 0 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'"
                      >
                        {{ product.stock }} unidades
                      </span>
                    </div>
                  </div>

                  <p class="text-gray-600 text-lg leading-relaxed">
                    {{ product.description || 'Sin descripción disponible.' }}
                  </p>
                </div>

                <!-- Quantity selector -->
                <div v-if="product.stock > 0" class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Cantidad
                    </label>
                    <div class="flex items-center space-x-4">
                      <button 
                        @click="decreaseQuantity"
                        :disabled="quantity <= 1"
                        class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                      </button>
                      
                      <span class="text-xl font-semibold w-12 text-center">{{ quantity }}</span>
                      
                      <button 
                        @click="increaseQuantity"
                        :disabled="quantity >= product.stock"
                        class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                      </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                      Máximo: {{ product.stock }} unidades
                    </p>
                  </div>

                  <!-- Add to cart button -->
                  <button 
                    @click="addToCart"
                    :disabled="addingToCart"
                    class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2"
                  >
                    <svg v-if="addingToCart" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <span v-if="addingToCart">Agregando...</span>
                    <span v-else>Agregar al Carrito</span>
                  </button>

                  <!-- Subtotal -->
                  <div class="text-center">
                    <p class="text-lg font-semibold text-gray-900">
                      Subtotal: ₲ {{ Number(product.price * quantity || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}
                    </p>
                  </div>
                </div>

                <!-- Out of stock message -->
                <div v-else class="text-center py-8 bg-red-50 rounded-lg">
                  <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
                  </svg>
                  <p class="text-red-600 font-medium">Producto sin stock</p>
                  <p class="text-red-500 text-sm mt-1">Este producto no está disponible en este momento</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import axios from 'axios'

// Props
const props = defineProps({
  id: {
    type: [String, Number],
    required: true
  }
})

// Reactive data
const product = ref(null)
const loading = ref(true)
const error = ref('')
const quantity = ref(1)
const addingToCart = ref(false)

// Methods
const loadProduct = async () => {
  try {
    loading.value = true
    error.value = ''
    
    const response = await axios.get(`/api/products/${props.id}`)
    
    if (response.data.success) {
      product.value = response.data.data
    } else {
      error.value = response.data.message || 'Error al cargar el producto'
    }
  } catch (err) {
    console.error('Error loading product:', err)
    if (err.response?.status === 404) {
      error.value = 'Producto no encontrado'
    } else {
      error.value = 'Error de conexión. Intente nuevamente.'
    }
  } finally {
    loading.value = false
  }
}

const increaseQuantity = () => {
  if (quantity.value < product.value.stock) {
    quantity.value++
  }
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const addToCart = async () => {
  try {
    addingToCart.value = true
    
    const response = await axios.post('/api/cart/add', {
      product_id: product.value.id,
      quantity: quantity.value
    })
    
    if (response.data.success) {
      alert('Producto agregado al carrito correctamente')
    } else {
      alert(response.data.message || 'Error al agregar producto al carrito')
    }
  } catch (err) {
    console.error('Error adding to cart:', err)
    alert('Error al agregar producto al carrito')
  } finally {
    addingToCart.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadProduct()
})
</script>
