<template>
  <component :is="layoutComponent">
    <div class="py-12">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-6">Datos de entrega</h3>
              <form @submit.prevent="submitOrder" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre y Apellido *</label>
                    <input 
                      v-model="form.customer_name" 
                      type="text" 
                      class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" 
                      required 
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                    <div class="flex">
                      <select 
                        v-model="form.phone_code" 
                        class="w-32 border-gray-300 rounded-l-md focus:ring-amber-500 focus:border-amber-500 border-r-0"
                      >
                        <option value="+595">🇵🇾 +595</option>
                        <option value="+54">🇦🇷 +54</option>
                        <option value="+55">🇧🇷 +55</option>
                        <option value="+56">🇨🇱 +56</option>
                        <option value="+57">🇨🇴 +57</option>
                        <option value="+598">🇺🇾 +598</option>
                      </select>
                      <input 
                        v-model="form.phone_number" 
                        type="tel" 
                        placeholder="987 654 321" 
                        class="flex-1 border-gray-300 rounded-r-md focus:ring-amber-500 focus:border-amber-500" 
                        @input="formatPhoneNumber"
                        required 
                      />
                    </div>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-3">Método de Pago *</label>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" 
                           :class="{ 'border-amber-500 bg-amber-50': form.payment_method === 'efectivo' }">
                      <input v-model="form.payment_method" type="radio" value="efectivo" class="mr-3">
                      <span class="flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <div>
                          <div class="font-medium">Efectivo</div>
                          <div class="text-sm text-gray-500">Paga al recibir</div>
                        </div>
                      </span>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" 
                           :class="{ 'border-amber-500 bg-amber-50': form.payment_method === 'tarjeta' }">
                      <input v-model="form.payment_method" type="radio" value="tarjeta" class="mr-3">
                      <span class="flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <div>
                          <div class="font-medium">Tarjeta</div>
                          <div class="text-sm text-gray-500">Pago seguro online</div>
                        </div>
                      </span>
                    </label>
                  </div>
                </div>

                <div v-if="form.payment_method === 'tarjeta'" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                  <h4 class="text-md font-semibold text-gray-800 mb-4">Información de Tarjeta</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Banco Emisor *</label>
                      <select 
                        v-model="form.card_issuer"
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                        required
                      >
                        <option value="">Selecciona tu banco</option>
                        <option value="UENO BANK">UENO BANK</option>
                        <option value="ITAU">ITAU</option>
                        <option value="BANCO FAMILIAR">BANCO FAMILIAR</option>
                        <option value="VISION BANK">VISION BANK</option>
                        <option value="BANCO CONTINENTAL">BANCO CONTINENTAL</option>
                        <option value="BANCO BBVA">BANCO BBVA</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Número de Tarjeta *</label>
                      <input 
                        v-model="form.card_number"
                        type="text"
                        placeholder="4242 4242 4242 4242"
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                        @input="formatCardNumber"
                        maxlength="19"
                        required
                      />
                    </div>
                  </div>
                  <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Expiración *</label>
                      <input 
                        v-model="form.card_expiration"
                        type="text"
                        placeholder="MM/AA"
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                        @input="formatExpiration"
                        maxlength="5"
                        required
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">CVC *</label>
                      <input 
                        v-model="form.card_cvc"
                        type="text"
                        placeholder="123"
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                        @input="formatCVC"
                        maxlength="4"
                        required
                      />
                    </div>
                  </div>
                  <div class="mt-4 p-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg text-white">
                    <div class="flex justify-between items-center">
                      <div class="text-sm">{{ form.card_issuer || 'BANCO' }}</div>
                      <div class="text-sm">💳</div>
                    </div>
                    <div class="mt-2 text-lg font-mono">
                      {{ form.card_number || '•••• •••• •••• ••••' }}
                    </div>
                    <div class="flex justify-between items-center mt-2 text-sm">
                      <div>{{ form.card_expiration || 'MM/AA' }}</div>
                      <div>{{ form.card_cvc ? '•'.repeat(form.card_cvc.length) : 'CVC' }}</div>
                    </div>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-3">Dirección de Entrega *</label>
                  <div class="space-y-4">
                    <div class="relative">
                      <input 
                        v-model="form.address" 
                        type="text" 
                        placeholder="Ingresa tu dirección completa" 
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 pr-32"
                        required 
                      />
                      <button 
                        type="button" 
                        @click="getCurrentLocation" 
                        :disabled="gettingLocation"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 flex items-center px-3 py-1 text-xs text-amber-600 hover:text-amber-700 border border-amber-300 rounded-md hover:bg-amber-50 transition-colors"
                      >
                        <svg v-if="gettingLocation" class="animate-spin h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-else class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ gettingLocation ? 'Buscando...' : 'Ubicación' }}
                      </button>
                    </div>
                    
                    <!-- Mapa simplificado -->
                    <div class="h-48 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 overflow-hidden flex items-center justify-center">
                      <div class="text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="text-sm font-medium">Sistema de ubicación</p>
                        <p class="text-xs mt-1">Usa el botón "Ubicación" para autocompletar</p>
                      </div>
                    </div>

                    <div v-if="currentLocation" class="flex items-center text-sm text-green-600 bg-green-50 p-2 rounded-md">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      Ubicación actual detectada
                    </div>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Notas para la entrega
                    <span class="text-gray-400 text-xs">(Opcional)</span>
                  </label>
                  <textarea 
                    v-model="form.notes" 
                    rows="3" 
                    class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" 
                    placeholder="Indica referencias de entrega, instrucciones especiales, etc."
                  ></textarea>
                </div>

                <div class="pt-4 border-t border-gray-200">
                  <button 
                    :disabled="submitting || !itemsList.length || !isFormValid" 
                    type="submit" 
                    class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-3 shadow-lg"
                  >
                    <svg v-if="submitting" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-lg">
                      {{ submitting ? 'Procesando Pago...' : `Confirmar Pedido - ₲ ${realTotalWithPersonalizations.toLocaleString('es-PY')}` }}
                    </span>
                  </button>
                  <p class="text-xs text-gray-500 text-center mt-3">
                    Al confirmar, aceptas nuestros términos y condiciones
                  </p>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div>
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-6">Resumen del Pedido</h3>
              <div v-if="!itemsList.length" class="text-gray-500 text-center py-8">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <p>Tu carrito está vacío</p>
              </div>
              <div v-else>
                <div class="space-y-4 mb-6">
                  <div v-for="item in itemsList" :key="item.id" class="border-b border-gray-100 pb-4 last:border-b-0">
                    <div class="flex justify-between items-start mb-2">
                      <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ item.name }}</h4>
                        <p class="text-sm text-gray-600">Cantidad: {{ item.quantity }}</p>
                        
                        <!-- Personalizaciones REALES solamente -->
                        <div v-if="hasRealPersonalizations(item.id)" class="mt-2 space-y-1">
                          <div v-if="getRealPersonalization(item.id, 'size')" class="flex justify-between text-xs">
                            <span class="text-gray-500">Tamaño:</span>
                            <span class="text-gray-700">{{ getRealPersonalization(item.id, 'size') }}</span>
                          </div>
                          <div v-if="getRealPersonalization(item.id, 'sugar')" class="flex justify-between text-xs">
                            <span class="text-gray-500">Azúcar:</span>
                            <span class="text-gray-700">{{ getRealPersonalization(item.id, 'sugar') }}</span>
                          </div>
                          <div v-if="hasRealToppings(item.id)" class="flex justify-between text-xs">
                            <span class="text-gray-500">Coberturas:</span>
                            <span class="text-gray-700">{{ getRealToppingsText(item.id) }}</span>
                          </div>
                          <div v-if="hasRealAddons(item.id)" class="flex justify-between text-xs">
                            <span class="text-gray-500">Agregados:</span>
                            <span class="text-gray-700">{{ getRealAddonsText(item.id) }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="text-right">
                        <p class="font-semibold text-gray-900">
                          ₲ {{ calculateRealItemTotal(item).toLocaleString('es-PY') }}
                        </p>
                        <p class="text-xs text-gray-500">
                          ₲ {{ (item.price || 0).toLocaleString('es-PY') }} c/u
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Desglose de Precios REAL -->
                <div class="space-y-3 text-sm">
                  <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal productos:</span>
                    <span class="font-medium">₲ {{ (cart.total || 0).toLocaleString('es-PY') }}</span>
                  </div>
                  
                  <div v-if="realTotalCustomizations > 0" class="flex justify-between">
                    <span class="text-gray-600">Personalizaciones:</span>
                    <span class="font-medium text-amber-600">+ ₲ {{ realTotalCustomizations.toLocaleString('es-PY') }}</span>
                  </div>
                  
                  <div class="flex justify-between">
                    <span class="text-gray-600">Envío:</span>
                    <span class="font-medium" :class="shippingCost > 0 ? 'text-gray-900' : 'text-green-600'">
                      {{ shippingCost > 0 ? `₲ ${shippingCost.toLocaleString('es-PY')}` : 'Gratis' }}
                    </span>
                  </div>
                  
                  <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between items-center">
                      <span class="text-lg font-bold text-gray-900">Total:</span>
                      <span class="text-lg font-bold text-amber-600">
                        ₲ {{ realTotalWithPersonalizations.toLocaleString('es-PY') }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Método de Pago Seleccionado -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                  <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Método de pago:</span>
                    <span class="font-medium capitalize">
                      {{ form.payment_method === 'tarjeta' ? 'Tarjeta' : 'Efectivo' }}
                    </span>
                  </div>
                  <div v-if="form.payment_method === 'tarjeta' && form.card_issuer" class="flex justify-between items-center text-sm mt-1">
                    <span class="text-gray-600">Banco:</span>
                    <span class="font-medium">{{ form.card_issuer }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </component>
</template>

<script setup>
import { computed, reactive, ref, onMounted } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  cart: {
    type: Object,
    default: () => ({ items: {}, total: 0, item_count: 0 })
  }
})

const page = usePage()
const layoutComponent = computed(() => page.props.auth?.user ? AuthenticatedLayout : GuestLayout)

const cart = computed(() => props.cart || { items: {}, total: 0, item_count: 0 })
const itemsList = computed(() => Object.values(cart.value.items || {}))

const form = reactive({
  customer_name: page.props.auth?.user?.name || '',
  phone_code: '+595',
  phone_number: '',
  address: '',
  payment_method: 'efectivo',
  notes: '',
  card_issuer: '',
  card_number: '',
  card_expiration: '',
  card_cvc: ''
})

const submitting = ref(false)
const gettingLocation = ref(false)
const currentLocation = ref(null)
const personalizations = ref({})
const shippingCost = ref(0)

// ✅ NUEVO: Reglas de personalización cargadas desde API
const customizationRules = ref({})

// ✅ NUEVO: Cargar reglas de personalización para todos los productos
const loadCustomizationRules = async () => {
  const rules = {}
  
  for (const item of itemsList.value) {
    try {
      const response = await fetch(`/api/products/${item.id}/customization-rules`)
      if (response.ok) {
        const productRules = await response.json()
        
        // ✅ APLICAR EXCLUSIONES COMO EN CartService.php
        const rulesWithExclusions = applyProductExclusions(productRules, item.id)
        rules[item.id] = rulesWithExclusions
        
        console.log(`✅ Reglas cargadas para producto ${item.id}:`, rulesWithExclusions)
      } else {
        console.warn(`⚠️ No se pudieron cargar reglas para producto ${item.id}`)
        rules[item.id] = getDefaultRules()
      }
    } catch (error) {
      console.error(`❌ Error cargando reglas para producto ${item.id}:`, error)
      rules[item.id] = getDefaultRules()
    }
  }
  
  customizationRules.value = rules
}

// ✅ NUEVO: Aplicar exclusiones específicas por producto (igual que en CartService.php)
const applyProductExclusions = (rules, productId) => {
  if (!rules || !rules.enabled_options) return rules
  
  // ✅ EXCLUSIONES ESPECÍFICAS POR PRODUCTO (igual que en el backend)
  const productExclusions = {
    // Ejemplo: Si el producto es agua (ID específico), quitar la opción de azúcar
    // Agregar aquí los IDs de productos que deben tener exclusiones
    // '123': ['sugar'], // Si el producto 123 es agua, quitar azúcar
  }
  
  if (productExclusions[productId]) {
    rules.enabled_options = rules.enabled_options.filter(
      option => !productExclusions[productId].includes(option)
    )
  }
  
  return rules
}

// ✅ NUEVO: Reglas por defecto
const getDefaultRules = () => ({
  enabled_options: ['quantity'],
  size_options: [],
  sugar_options: [],
  topping_options: [],
  addon_options: [],
})

// ✅ NUEVO: Helper functions para calcular precios REALES
const getSizePrice = (productId, size) => {
  const sizeOptions = customizationRules.value[productId]?.size_options || []
  const sizeOption = sizeOptions.find(opt => opt.name === size)
  return sizeOption?.price || 0
}

const getToppingsPrice = (productId, toppings) => {
  if (!Array.isArray(toppings) || toppings.length === 0) return 0
  
  const toppingOptions = customizationRules.value[productId]?.topping_options || []
  return toppings.reduce((total, toppingName) => {
    const topping = toppingOptions.find(t => t.name === toppingName)
    return total + (topping?.price || 0)
  }, 0)
}

const getAddonsPrice = (productId, addons) => {
  if (!Array.isArray(addons) || addons.length === 0) return 0
  
  const addonOptions = customizationRules.value[productId]?.addon_options || []
  return addons.reduce((total, addonName) => {
    const addon = addonOptions.find(a => a.name === addonName)
    return total + (addon?.price || 0)
  }, 0)
}

// ✅ NUEVO: Verificar si una opción está habilitada considerando exclusiones
const isOptionEnabled = (productId, option) => {
  const rules = customizationRules.value[productId]
  return rules?.enabled_options?.includes(option) || false
}

// Cargar datos al montar
onMounted(async () => {
  const storedPersonalizations = sessionStorage.getItem('personalizations')
  const storedShipping = sessionStorage.getItem('shippingCost')
  
  if (storedPersonalizations) {
    try {
      personalizations.value = JSON.parse(storedPersonalizations)
      console.log('✅ Personalizaciones cargadas:', personalizations.value)
    } catch (error) {
      console.error('❌ Error parsing personalizations:', error)
      personalizations.value = {}
    }
  }
  
  if (storedShipping) {
    shippingCost.value = parseInt(storedShipping) || 0
  }
  
  // ✅ NUEVO: Cargar reglas de personalización
  await loadCustomizationRules()
})

// ✅ CORREGIDO: Considerar exclusiones en las personalizaciones reales
const hasRealPersonalizations = (productId) => {
  const personalization = personalizations.value[productId]
  if (!personalization) return false
  
  // ✅ SOLO considerar opciones que están HABILITADAS según las reglas
  const hasSize = isOptionEnabled(productId, 'size') && 
                 personalization.size && 
                 personalization.size !== 'Mediano'
  
  const hasSugar = isOptionEnabled(productId, 'sugar') && 
                   personalization.sugar && 
                   personalization.sugar !== 'Normal'
  
  const hasToppings = isOptionEnabled(productId, 'toppings') && 
                      Array.isArray(personalization.toppings) && 
                      personalization.toppings.length > 0
  
  const hasAddons = isOptionEnabled(productId, 'addons') && 
                    Array.isArray(personalization.addons) && 
                    personalization.addons.length > 0
  
  return hasSize || hasSugar || hasToppings || hasAddons
}

const getRealPersonalization = (productId, field) => {
  // ✅ SOLO mostrar si la opción está habilitada
  if (!isOptionEnabled(productId, field === 'toppings' || field === 'addons' ? field : field === 'size' ? 'size' : 'sugar')) {
    return null
  }
  
  const value = personalizations.value[productId]?.[field]

  return value
}

const hasRealToppings = (productId) => {
  // ✅ SOLO si toppings está habilitado
  if (!isOptionEnabled(productId, 'toppings')) return false
  
  const toppings = personalizations.value[productId]?.toppings
  return Array.isArray(toppings) && toppings.length > 0
}

const hasRealAddons = (productId) => {
  // ✅ SOLO si addons está habilitado
  if (!isOptionEnabled(productId, 'addons')) return false
  
  const addons = personalizations.value[productId]?.addons
  return Array.isArray(addons) && addons.length > 0
}

const getRealToppingsText = (productId) => {
  const toppings = personalizations.value[productId]?.toppings || []
  return toppings.join(', ')
}

const getRealAddonsText = (productId) => {
  const addons = personalizations.value[productId]?.addons || []
  return addons.join(', ')
}

// ✅ CORREGIDO: Cálculos usando precios REALES de las reglas
const calculateRealItemTotal = (item) => {
  const basePrice = item.price || 0
  const personalization = personalizations.value[item.id] || {}
  
  // ✅ USAR PRECIOS REALES DE LAS REGLAS, solo para opciones habilitadas
  const sizePrice = isOptionEnabled(item.id, 'size') ? getSizePrice(item.id, personalization.size) || 0 : 0
  const toppingsPrice = isOptionEnabled(item.id, 'toppings') ? getToppingsPrice(item.id, personalization.toppings) || 0 : 0
  const addonsPrice = isOptionEnabled(item.id, 'addons') ? getAddonsPrice(item.id, personalization.addons) || 0 : 0
  
  const finalUnitPrice = basePrice + sizePrice + toppingsPrice + addonsPrice
  return finalUnitPrice * (item.quantity || 1)
}

// ✅ CORREGIDO: Total de personalizaciones usando precios REALES
const realTotalCustomizations = computed(() => {
  let total = 0
  itemsList.value.forEach(item => {
    const personalization = personalizations.value[item.id] || {}
    
    // ✅ SOLO calcular para opciones HABILITADAS
    const sizePrice = isOptionEnabled(item.id, 'size') ? getSizePrice(item.id, personalization.size) || 0 : 0
    const toppingsPrice = isOptionEnabled(item.id, 'toppings') ? getToppingsPrice(item.id, personalization.toppings) || 0 : 0
    const addonsPrice = isOptionEnabled(item.id, 'addons') ? getAddonsPrice(item.id, personalization.addons) || 0 : 0
    
    total += (sizePrice + toppingsPrice + addonsPrice) * (item.quantity || 1)
  })
  return total
})

// ✅ CORREGIDO: Total final con personalizaciones REALES
const realTotalWithPersonalizations = computed(() => {
  const baseTotal = cart.value.total || 0
  return baseTotal + realTotalCustomizations.value + shippingCost.value
})

// Validación del formulario
const isFormValid = computed(() => {
  const basicFields = form.customer_name && form.phone_number && form.address
  if (form.payment_method === 'tarjeta') {
    return basicFields && form.card_issuer && form.card_number && 
           form.card_expiration && form.card_cvc
  }
  return basicFields
})

// Formateadores de input (se mantienen igual)
const formatPhoneNumber = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length > 9) value = value.slice(0, 9)
  if (value.length > 6) {
    value = value.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1 $2 $3')
  } else if (value.length > 3) {
    value = value.replace(/(\d{3})(\d{0,3})/, '$1 $2')
  }
  form.phone_number = value.trim()
}

const formatCardNumber = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length > 16) value = value.slice(0, 16)
  value = value.replace(/(\d{4})/g, '$1 ').trim()
  if (value.endsWith(' ')) value = value.slice(0, -1)
  form.card_number = value
}

const formatExpiration = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length > 4) value = value.slice(0, 4)
  if (value.length >= 2) {
    value = value.replace(/(\d{2})(\d{0,2})/, '$1/$2')
  }
  form.card_expiration = value
}

const formatCVC = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length > 4) value = value.slice(0, 4)
  form.card_cvc = value
}

// Ubicación actual (se mantiene igual)
const getCurrentLocation = () => {
  if (!navigator.geolocation) {
    alert('La geolocalización no está soportada por este navegador.')
    return
  }
  gettingLocation.value = true
  navigator.geolocation.getCurrentPosition(
    (position) => {
      const { latitude, longitude } = position.coords
      currentLocation.value = { latitude, longitude }
      
      fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
        .then(response => response.json())
        .then(data => {
          if (data.display_name) {
            form.address = data.display_name
          }
          gettingLocation.value = false
        })
        .catch(error => {
          console.error('Error al obtener dirección:', error)
          gettingLocation.value = false
          alert('No se pudo obtener la dirección. Por favor ingrésala manualmente.')
        })
    },
    (error) => {
      console.error('Error de geolocalización:', error)
      gettingLocation.value = false
      let message = 'No se pudo obtener tu ubicación. '
      switch(error.code) {
        case error.PERMISSION_DENIED:
          message += 'Permiso denegado para acceder a la ubicación.'
          break
        case error.POSITION_UNAVAILABLE:
          message += 'Información de ubicación no disponible.'
          break
        case error.TIMEOUT:
          message += 'Tiempo de espera agotado.'
          break
        default:
          message += 'Error desconocido.'
          break
      }
      alert(message)
    },
    {
      enableHighAccuracy: true,
      timeout: 10000,
      maximumAge: 60000
    }
  )
}

// Envío del formulario (se mantiene igual)
const submitOrder = () => {
  if (!isFormValid.value) {
    alert('Por favor completa todos los campos requeridos.')
    return
  }
  submitting.value = true
  const orderData = {
    ...form,
    phone: `${form.phone_code} ${form.phone_number}`.trim(),
    personalizations: personalizations.value,
    shipping_cost: shippingCost.value,
    ...(form.payment_method === 'tarjeta' && {
      card_issuer: form.card_issuer,
      card_last_four: form.card_number.replace(/\s/g, '').slice(-4),
      card_expiration: form.card_expiration
    })
  }
  console.log('Enviando datos al checkout:', orderData)
  router.post('/checkout', orderData, {
    onFinish: () => { submitting.value = false },
    onError: (errors) => {
      console.error('Errores en el checkout:', errors)
      alert('Error al procesar el pedido. Por favor verifica los datos.')
    }
  })
}
</script>

<style scoped>
input:focus, select:focus, textarea:focus {
  outline: none;
}
.transition-colors {
  transition: all 0.2s ease-in-out;
}
button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>