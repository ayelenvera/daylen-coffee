<template>
  <Head title="Carrito - Cafetería" />
  
  <component :is="layoutComponent">

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- ✅ Verificar si el usuario es administrador -->
        <div v-if="$page.props.auth.user && $page.props.auth.user.is_admin" class="text-center py-12">
          <div class="text-amber-600 mb-6">
            <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Acceso restringido</h3>
          <p class="text-gray-600 mb-6">Los administradores no pueden acceder al carrito de compras.</p>
          <a href="/admin/dashboard" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg">
            Ir al Panel de Administración
          </a>
        </div>

        <!-- Empty cart para usuarios normales -->
        <div v-else-if="!itemsList.length" class="text-center py-12">
          <div class="text-gray-400 mb-6">
            <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Tu carrito está vacío</h3>
          <p class="text-gray-600 mb-6">Agrega algunos productos deliciosos para comenzar tu pedido</p>
          <a href="/home" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg">
            Ver Productos
          </a>
        </div>

        <!-- Cart with Items - DISEÑO MEJORADO -->
        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <!-- Sección Izquierda - Productos -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
              <!-- Header -->
              <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Tu Carrito</h2>
                <p class="text-sm text-gray-600 mt-1">{{ itemsList.length }} {{ itemsList.length === 1 ? 'producto' : 'productos' }}</p>
              </div>

              <!-- Lista de Productos -->
              <div class="divide-y divide-gray-100">
                <div 
                  v-for="item in itemsList" 
                  :key="item.id"
                  class="p-6 hover:bg-gray-50 transition-colors duration-200"
                >
                  <div class="flex gap-4">
                    <!-- Imagen -->
                    <div class="flex-shrink-0">
                      <img 
                        :src="item.image" 
                        :alt="item.name"
                        class="w-20 h-20 rounded-lg object-cover border border-gray-200"
                        @error="(e) => { e.target.onerror = null; e.target.src = '/images/placeholder.jpg' }"
                      />
                    </div>

                    <!-- Información del Producto -->
                    <div class="flex-1 min-w-0">
                      <div class="flex justify-between items-start">
                        <div>
                          <h3 class="text-lg font-semibold text-gray-900">{{ item.name }}</h3>
                          <p class="text-amber-600 font-medium mt-1">
                            ₲ {{ Number(item.price || 0).toLocaleString('es-PY') }}
                            <span v-if="getSizePrice(item.id)" class="text-sm text-gray-500">
                              + ₲ {{ getSizePrice(item.id).toLocaleString('es-PY') }} ({{ getSelectedSizeName(item.id) }})
                            </span>
                          </p>
                        </div>
                        <div class="text-right">
                          <p class="text-xl font-bold text-gray-900">
                            ₲ {{ calculateItemTotal(item).toLocaleString('es-PY') }}
                          </p>
                          <p class="text-xs text-gray-500 mt-1">
                            {{ getSelectedSizeName(item.id) }} 
                            <span v-if="localEdits[item.id]?.sugar">· {{ localEdits[item.id].sugar }}</span>
                          </p>
                        </div>
                      </div>

                      <!-- OPCIONES DE PERSONALIZACIÓN DINÁMICAS -->
                      <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Cantidad (siempre visible) -->
                        <div class="flex items-center gap-2">
                          <label class="text-sm font-medium text-gray-700">Cantidad:</label>
                          <input 
                            type="number" 
                            min="1" 
                            v-model.number="localEdits[item.id].quantity"
                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                          />
                        </div>

                        <!-- Tamaño (solo si está habilitado) -->
                        <div class="flex items-center gap-2" v-if="customizationRules[item.id]?.enabled_options?.includes('size') && customizationRules[item.id]?.size_options">
                          <label class="text-sm font-medium text-gray-700">Tamaño:</label>
                          <select 
                            v-model="localEdits[item.id].size"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 min-w-[140px]"
                          >
                            <option 
                              v-for="sizeOpt in customizationRules[item.id].size_options" 
                              :key="sizeOpt.name"
                              :value="sizeOpt.name"
                            >
                              {{ sizeOpt.name }} (+₲{{ (sizeOpt.price || 0).toLocaleString('es-PY') }})
                            </option>
                          </select>
                        </div>

                        <!-- Azúcar (solo si está habilitado) -->
                        <div class="flex items-center gap-2" v-if="customizationRules[item.id]?.enabled_options?.includes('sugar') && customizationRules[item.id]?.sugar_options">
                          <label class="text-sm font-medium text-gray-700">Azúcar:</label>
                          <select 
                            v-model="localEdits[item.id].sugar"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 min-w-[120px]"
                          >
                            <option v-for="sugarOpt in customizationRules[item.id].sugar_options" :key="sugarOpt" :value="sugarOpt">
                              {{ sugarOpt }}
                            </option>
                          </select>
                        </div>
                      </div>

                      <!-- Coberturas (solo si está habilitado) -->
                      <div v-if="customizationRules[item.id]?.enabled_options?.includes('toppings') && customizationRules[item.id]?.topping_options" class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Coberturas:</label>
                        <div class="flex flex-wrap gap-2">
                          <label 
                            v-for="topping in customizationRules[item.id].topping_options" 
                            :key="topping.name"
                            class="flex items-center space-x-2 p-2 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer"
                            :class="{ 'bg-amber-50 border-amber-300': localEdits[item.id].toppings?.includes(topping.name) }"
                          >
                            <input 
                              type="checkbox" 
                              :value="topping.name"
                              v-model="localEdits[item.id].toppings"
                              class="rounded border-gray-300 text-amber-600 focus:ring-amber-500"
                            >
                            <span class="text-sm text-gray-700">{{ topping.name }} (+₲{{ (topping.price || 0).toLocaleString('es-PY') }})</span>
                          </label>
                        </div>
                      </div>

                      <!-- Agregados (solo si está habilitado) -->
                      <div v-if="customizationRules[item.id]?.enabled_options?.includes('addons') && customizationRules[item.id]?.addon_options" class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Agregados:</label>
                        <div class="flex flex-wrap gap-2">
                          <label 
                            v-for="addon in customizationRules[item.id].addon_options" 
                            :key="addon.name"
                            class="flex items-center space-x-2 p-2 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer"
                            :class="{ 'bg-amber-50 border-amber-300': localEdits[item.id].addons?.includes(addon.name) }"
                          >
                            <input 
                              type="checkbox" 
                              :value="addon.name"
                              v-model="localEdits[item.id].addons"
                              class="rounded border-gray-300 text-amber-600 focus:ring-amber-500"
                            >
                            <span class="text-sm text-gray-700">{{ addon.name }} (+₲{{ (addon.price || 0).toLocaleString('es-PY') }})</span>
                          </label>
                        </div>
                      </div>

                      <!-- Botones de Acción -->
                      <div class="mt-4 flex gap-3">
                        <button 
                          @click="updateItem(item.id)"
                          class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                        >
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          Actualizar
                        </button>
                        
                        <button 
                          @click="removeFromCart(item.id)"
                          :disabled="removing === item.id"
                          class="inline-flex items-center px-4 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 text-sm font-medium rounded-lg transition-colors duration-200 disabled:opacity-50"
                        >
                          <svg v-if="removing === item.id" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                          <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                          {{ removing === item.id ? 'Eliminando...' : 'Eliminar' }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Botón Limpiar Carrito - CENTRADO Y MEJORADO -->
              <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                <div class="flex justify-center">
                  <button 
                    @click="clearCart"
                    :disabled="clearing"
                    class="inline-flex items-center px-6 py-3 text-red-600 hover:text-red-800 hover:bg-red-50 font-medium rounded-lg transition-colors duration-200 border border-red-200 disabled:opacity-50"
                  >
                    <svg v-if="clearing" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    {{ clearing ? 'Limpiando carrito...' : 'Limpiar todo el carrito' }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Sección Derecha - Resumen -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-6">
              <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Resumen del Pedido</h3>

                <div class="space-y-4">
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Subtotal productos:</span>
                    <span class="font-semibold text-gray-900">
                      ₲ {{ Number(cartData.total || 0).toLocaleString('es-PY') }}
                    </span>
                  </div>

                  <div class="flex justify-between items-center" v-if="totalCustomizations > 0">
                    <span class="text-gray-600">Personalizaciones:</span>
                    <span class="font-semibold text-amber-600">
                      + ₲ {{ totalCustomizations.toLocaleString('es-PY') }}
                    </span>
                  </div>
                  
                  <!-- ENVÍO MEJORADO -->
                  <div class="flex justify-between items-center">
                    <span class="text-gray-600">Envío:</span>
                    <span class="font-semibold" :class="shippingCost > 0 ? 'text-gray-900' : 'text-green-600'">
                      {{ shippingCost > 0 ? `₲ ${shippingCost.toLocaleString('es-PY')}` : 'Gratis' }}
                    </span>
                  </div>

                  <!-- Selector de Zona de Envío -->
                  <div class="border-t border-gray-200 pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Zona de envío:</label>
                    <select 
                      v-model="selectedZone"
                      @change="calculateShipping"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    >
                      <option value="local">Ciudad del Este (₲ 5.000)</option>
                      <option value="medium">Pdte. Franco (₲ 7.000)</option>
                      <option value="far">Hernandarias (₲ 12.000)</option>
                    </select>
                  </div>
                  
                  <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center">
                      <span class="text-lg font-bold text-gray-900">Total:</span>
                      <span class="text-lg font-bold text-amber-600">
                        ₲ {{ Number(totalWithShipping).toLocaleString('es-PY') }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Botón Checkout -->
                <button 
                  @click="proceedToCheckout"
                  :disabled="checkingOut"
                  class="w-full mt-6 bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-3"
                >
                  <svg v-if="checkingOut" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10M8 15l3-3 3 3m5-8a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>{{ checkingOut ? 'Procesando...' : 'Proceder al Pago' }}</span>
                </button>

                <a href="/home" class="w-full mt-4 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-4 px-6 rounded-lg transition-colors duration-200 text-center block">
                  Continuar Comprando
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </component>
</template>

<script setup>
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props y estado
const props = defineProps({
  cart: {
    type: Object,
    default: () => ({ items: [], total: 0, item_count: 0 })
  }
})

const removing = ref(null)
const clearing = ref(false)
const checkingOut = ref(false)
const selectedZone = ref('local')
const shippingCost = ref(0)

// Datos derivados
const page = usePage()
const cartData = computed(() => page.props.cart || { items: {}, total: 0, item_count: 0 })
const itemsList = computed(() => Object.values(cartData.value.items || {}))

// Reglas de personalización por producto
const customizationRules = reactive({})

// Cargar reglas de personalización para cada producto
const loadCustomizationRules = async () => {
  for (const item of itemsList.value) {
    try {
      const response = await fetch(`/api/products/${item.id}/customization-rules`)
      const rules = await response.json()
      customizationRules[item.id] = rules
      
      console.log(`Reglas para producto ${item.id}:`, rules)
    } catch (error) {
      console.error('Error loading customization rules:', error)
      // Reglas por defecto si hay error
      customizationRules[item.id] = {
        enabled_options: ['quantity'],
        size_options: [],
        sugar_options: [],
        topping_options: [],
        addon_options: []
      }
    }
  }
}

// Calcular total con personalizaciones
const totalCustomizations = computed(() => {
  let total = 0
  itemsList.value.forEach(item => {
    const sizePrice = getSizePrice(item.id) || 0
    const toppingsPrice = getToppingsPrice(item.id) || 0
    const addonsPrice = getAddonsPrice(item.id) || 0
    total += (sizePrice + toppingsPrice + addonsPrice) * (localEdits[item.id]?.quantity || 1)
  })
  return total
})

// Total con envío y personalizaciones
const totalWithShipping = computed(() => {
  const baseTotal = cartData.value.total || 0
  return baseTotal + totalCustomizations.value + shippingCost.value
})

// Calcular total por item
const calculateItemTotal = (item) => {
  const basePrice = item.price || 0
  const sizePrice = getSizePrice(item.id) || 0
  const toppingsPrice = getToppingsPrice(item.id) || 0
  const addonsPrice = getAddonsPrice(item.id) || 0
  const quantity = localEdits[item.id]?.quantity || 1
  
  return (basePrice + sizePrice + toppingsPrice + addonsPrice) * quantity
}

// Helper functions
const getSizePrice = (productId) => {
  const selectedSize = localEdits[productId]?.size
  const sizeOptions = customizationRules[productId]?.size_options || []
  const sizeOption = sizeOptions.find(opt => opt.name === selectedSize)
  return sizeOption?.price || 0
}

const getSelectedSizeName = (productId) => {
  return localEdits[productId]?.size || 'Mediano'
}

const getToppingsPrice = (productId) => {
  const selectedToppings = localEdits[productId]?.toppings || []
  const toppingOptions = customizationRules[productId]?.topping_options || []
  return selectedToppings.reduce((total, toppingName) => {
    const topping = toppingOptions.find(t => t.name === toppingName)
    return total + (topping?.price || 0)
  }, 0)
}

const getAddonsPrice = (productId) => {
  const selectedAddons = localEdits[productId]?.addons || []
  const addonOptions = customizationRules[productId]?.addon_options || []
  return selectedAddons.reduce((total, addonName) => {
    const addon = addonOptions.find(a => a.name === addonName)
    return total + (addon?.price || 0)
  }, 0)
}

// Calcular costo de envío
const calculateShipping = () => {
  const zonePrices = {
    'local': 5000,
    'medium': 7000,
    'far': 12000
  }
  shippingCost.value = zonePrices[selectedZone.value] || 0
}

// Opciones por defecto
const localEdits = reactive({})

// En syncLocalEdits(), reemplazar:
const syncLocalEdits = () => {
  itemsList.value.forEach(it => {
    if (!localEdits[it.id]) {
      // ✅ CORREGIDO: Solo usar tamaño por defecto si está habilitado y disponible
      const rules = customizationRules[it.id] || {};
      const hasSizeEnabled = rules.enabled_options?.includes('size');
      const defaultSize = hasSizeEnabled ? (rules.default_size || null) : null;
      
      localEdits[it.id] = {
        quantity: it.quantity || 1,
        size: it.size || defaultSize, // ✅ NULL si no tiene tamaño habilitado
        sugar: it.sugar || 'Normal',
        toppings: it.toppings || [],
        addons: it.addons || []
      }
    }
  });
}

// Watch y mounted
watch(cartData, () => {
  syncLocalEdits()
  loadCustomizationRules()
}, { immediate: true, deep: true })

onMounted(() => {
  calculateShipping()
})

// Acciones
const removeFromCart = async (productId) => {
  removing.value = productId
  await router.post('/cart/remove', { product_id: productId }, {
    preserveScroll: true,
    onSuccess: () => { router.reload({ only: ['cart'] }) },
    onFinish: () => { removing.value = null }
  })
}

const clearCart = async () => {
  if (!confirm('¿Estás seguro de que quieres limpiar todo el carrito?')) return
  clearing.value = true
  await router.delete('/cart/clear', {
    preserveScroll: true,
    onSuccess: () => { router.reload({ only: ['cart'] }) },
    onFinish: () => { clearing.value = false }
  })
}

const updateItem = async (productId) => {
  const payload = { 
    product_id: productId, 
    ...localEdits[productId] 
  }
  await router.post('/cart/update', payload, {
    preserveScroll: true,
    onSuccess: () => { router.reload({ only: ['cart'] }) },
  })
}

// En el método proceedToCheckout(), reemplazar:
const proceedToCheckout = async () => {
  checkingOut.value = true
  
  // ✅ CORREGIDO: Solo guardar personalizaciones de productos actuales en el carrito
  const currentProductIds = itemsList.value.map(item => item.id);
  const personalizations = {}
  
  itemsList.value.forEach(item => {
    personalizations[item.id] = {
      toppings: localEdits[item.id]?.toppings || [],
      addons: localEdits[item.id]?.addons || [],
      size: localEdits[item.id]?.size || null, // ✅ NULL en lugar de 'Mediano' por defecto
      sugar: localEdits[item.id]?.sugar || 'Normal'
    }
  });
  
  // ✅ CORREGIDO: Limpiar personalizaciones de productos que ya no están en el carrito
  sessionStorage.setItem('personalizations', JSON.stringify(personalizations));
  sessionStorage.setItem('shippingCost', shippingCost.value);
  sessionStorage.setItem('selectedZone', selectedZone.value);
  sessionStorage.setItem('totalCustomizations', totalCustomizations.value);
  
  router.visit('/checkout', { onFinish: () => { checkingOut.value = false } });
}

// Layout dinámico
const layoutComponent = computed(() => page.props.auth?.user ? AuthenticatedLayout : GuestLayout)
</script>