<template>
  <Head title="Checkout - Cafetería" />
  
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
          Confirmación de Pago
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Success message -->
        <div v-if="orderData" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="text-center">
              <!-- Success icon -->
              <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>

              <!-- Success message -->
              <h1 class="text-3xl font-bold text-gray-900 mb-4">
                ¡Pago Simulado Exitoso!
              </h1>
              
              <p class="text-lg text-gray-600 mb-8">
                Tu pedido ha sido procesado correctamente. Aquí tienes los detalles:
              </p>

              <!-- Order details card -->
              <div class="bg-gray-50 rounded-lg p-6 mb-8 max-w-md mx-auto">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalles del Pedido</h2>
                
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-gray-600">Número de Pedido:</span>
                    <span class="font-semibold text-gray-900">#{{ orderData.order_id }}</span>
                  </div>
                  
                  <div class="flex justify-between">
                    <span class="text-gray-600">Total Pagado:</span>
                    <span class="font-bold text-amber-600 text-lg">₲ {{ Number(orderData.total || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                  </div>
                  
                  <div class="flex justify-between">
                    <span class="text-gray-600">ID de Pago:</span>
                    <span class="font-mono text-sm text-gray-700">{{ orderData.payment_id }}</span>
                  </div>
                  
                  <div class="flex justify-between">
                    <span class="text-gray-600">Estado:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      Pagado
                    </span>
                  </div>
                  
                  <div class="flex justify-between">
                    <span class="text-gray-600">Fecha:</span>
                    <span class="text-gray-700">{{ formatDate(new Date()) }}</span>
                  </div>
                </div>
              </div>

              <!-- Information about simulation -->
              <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                      Información Importante
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                      <p>
                        Este es un <strong>pago simulado</strong> para fines de demostración. 
                        En una implementación real, aquí se integraría con un procesador de pagos 
                        como MercadoPago, Stripe, o PayPal.
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action buttons -->
              <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <Link 
                  :href="route('home')" 
                  class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200"
                >
                  Continuar Comprando
                </Link>
                
                <Link 
                  :href="route('orders')" 
                  class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200"
                >
                  Ver Mis Pedidos
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Loading state -->
        <div v-else-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
          <p class="mt-4 text-gray-600">Procesando tu pedido...</p>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="text-center py-12">
          <div class="text-red-600 mb-6">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Error en el Proceso</h2>
          <p class="text-red-600 font-medium mb-6">{{ error }}</p>
          <div class="space-x-4">
            <Link 
              :href="route('cart')" 
              class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg"
            >
              Volver al Carrito
            </Link>
            <Link 
              :href="route('home')" 
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg"
            >
              Ir al Inicio
            </Link>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

// Props from URL parameters
const props = defineProps({
  order_id: {
    type: [String, Number],
    required: true
  },
  total: {
    type: [String, Number],
    required: true
  },
  payment_id: {
    type: String,
    required: true
  }
})

// Reactive data
const orderData = ref(null)
const loading = ref(true)
const error = ref('')

// Methods
const formatDate = (date) => {
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const initializeOrderData = () => {
  try {
    // Simular un pequeño delay para mostrar el loading
    setTimeout(() => {
      orderData.value = {
        order_id: props.order_id,
        total: parseFloat(props.total),
        payment_id: props.payment_id,
        status: 'paid'
      }
      loading.value = false
    }, 1500)
  } catch (err) {
    console.error('Error initializing order data:', err)
    error.value = 'Error al procesar los datos del pedido'
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  initializeOrderData()
})
</script>
