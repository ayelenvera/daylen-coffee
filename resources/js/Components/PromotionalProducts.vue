<template>
    <section v-if="products.length > 0" class="promotional-section mb-8">
      <!-- Encabezado -->
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
          <div class="bg-gradient-to-r from-red-500 to-orange-500 p-2 rounded-full">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">Ofertas Especiales</h2>
        </div>
        <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200">
          {{ products.length }} oferta{{ products.length !== 1 ? 's' : '' }} activa{{ products.length !== 1 ? 's' : '' }}
        </span>
      </div>
  
      <!-- Grid de productos -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="product in products" 
          :key="product.id"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-200 relative"
        >
          <!-- Badge de descuento -->
          <div class="absolute top-3 right-3 z-10">
            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
              {{ product.discount_percentage }}% OFF
            </span>
          </div>
  
          <!-- Imagen del producto -->
          <div class="relative h-48 overflow-hidden">
            <img 
              :src="product.image_url" 
              :alt="product.name"
              class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
            />
            <div class="absolute inset-0 bg-black bg-opacity-10"></div>
          </div>
  
          <!-- Contenido del producto -->
          <div class="p-4">
            <!-- Categoría -->
            <div class="mb-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                <span class="mr-1">{{ product.category_relation?.emoji || '📦' }}</span>
                {{ product.category_relation?.name || 'Sin categoría' }}
              </span>
            </div>
            
            <!-- Nombre -->
            <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-1">{{ product.name }}</h3>
            
            <!-- Descripción -->
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ product.description }}</p>
  
            <!-- Precios -->
            <div class="space-y-2">
              <!-- Precio original tachado -->
              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500 line-through">
                  ₲ {{ formatPrice(product.price) }}
                </span>
              </div>
              
              <!-- Precio promocional -->
              <div class="flex items-baseline justify-between">
                <span class="text-2xl font-bold text-green-600">
                  ₲ {{ formatPrice(product.promotional_price) }}
                </span>
                
                <!-- Ahorro -->
                <span class="text-sm text-green-600 font-medium bg-green-50 px-2 py-1 rounded">
                  Ahorras ₲ {{ formatPrice(product.savings_amount) }}
                </span>
              </div>
  
              <!-- Stock y botón -->
              <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                <span class="text-xs text-gray-500">
                  Stock: {{ product.stock }}
                </span>
                
                <!-- Botón agregar al carrito -->
                <button 
                  @click="addToCart(product)"
                  :disabled="addingToCart === product.id || product.stock === 0"
                  class="bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center space-x-1"
                >
                  <svg v-if="addingToCart === product.id" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                  <span>{{ addingToCart === product.id ? 'Agregando...' : 'Agregar' }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { router, usePage } from '@inertiajs/vue3' // ✅ AGREGAR usePage
  
  // Props
  const props = defineProps({
    products: {
      type: Array,
      default: () => []
    }
  })
  
  // Estado reactivo
  const addingToCart = ref(null)
  
  // Formatear precio
  const formatPrice = (price) => {
    return new Intl.NumberFormat('es-PY').format(price)
  }
  
  // Reemplazar el método addToCart actual por este:
  const addToCart = async (product) => {
    // ✅ VERIFICAR SI EL USUARIO ESTÁ AUTENTICADO
    if (!usePage().props.auth.user) {
      // Redirigir al login con mensaje específico
      window.location.href = '/login?message=Por favor inicia sesión para agregar productos en promoción al carrito';
      return;
    }

    // ✅ VERIFICAR QUE NO SEA ADMIN
    if (usePage().props.auth.user.is_admin) {
      alert('Los administradores no pueden agregar productos al carrito');
      return;
    }

    try {
      addingToCart.value = product.id
    
      await router.post('/cart/add', {
        product_id: product.id,
        quantity: 1
      }, {
        preserveScroll: true,
        onSuccess: () => {
          // Recargar el contador del carrito
          router.reload({ only: ['cartCount'] })
        },
        onError: () => {
          console.error('Error al agregar al carrito')
        },
        onFinish: () => {
          addingToCart.value = null
        }
      })
    } catch (err) {
      console.error('Error adding to cart:', err)
      addingToCart.value = null
    }
  }
  </script>
  
  <style scoped>
  .promotional-section {
    background: linear-gradient(135deg, #fff5f5 0%, #fffbeb 100%);
    border: 1px solid #fed7aa;
    border-radius: 12px;
    padding: 24px;
  }
  
  .line-clamp-1 {
    display: -webkit-box;
    /*-webkit-line-clamp: 1;*/
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .line-clamp-2 {
    display: -webkit-box;
    /*-webkit-line-clamp: 2;*/
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  </style>