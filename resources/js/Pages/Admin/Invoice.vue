<template>
  <Head title="Factura - Cafetería Daylen" />

  <AuthenticatedLayout>
    <div class="py-12 relative">
      <!-- Botón de cierre mejorado -->
      <Link 
        :href="route('admin.orders.index')" 
        class="fixed top-4 right-4 z-50 group"
        title="Cerrar factura"
      >
        <div class="relative w-12 h-12 flex items-center justify-center">
          <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-red-600 rounded-full transform group-hover:scale-110 transition-transform duration-200 shadow-lg"></div>
          <svg class="relative w-6 h-6 text-white transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </div>
      </Link>

      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 transform transition-all duration-300 hover:scale-[1.01]">
        <!-- Invoice Container -->
        <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-amber-100" id="invoice-content">
          <!-- Header -->
          <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-8 px-10 relative overflow-hidden">
            <!-- Patrón decorativo sutil -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500 opacity-10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-500 opacity-5 rounded-full -ml-32 mb-10"></div>
            
            <div class="relative z-10">
              <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-6 md:mb-0">
                  <div class="flex items-center">
                    <h1 class="text-3xl font-bold font-serif">☕ Daylen</h1>
                    <span class="ml-2 bg-amber-800 text-amber-100 text-xs px-2 py-1 rounded-full">PRO</span>
                  </div>
                  <p class="text-amber-100 mt-1">Sistema de Facturación</p>
                  <p class="text-amber-200 text-sm mt-1">Factura Electrónica</p>
                </div>
                <div class="text-right">
                  <div class="inline-block bg-white/10 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-xl font-bold text-lg text-white shadow-lg">
                    FACTURA #{{ order.id.toString().padStart(6, '0') }}
                  </div>
                  <p class="text-amber-100 text-sm mt-2">Emitida: {{ formatDate(order.created_at) }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Invoice Details -->
          <div class="p-10">
            <!-- Sección de información -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
              <!-- Información de la Empresa -->
              <div class="bg-amber-50 p-6 rounded-xl border border-amber-100">
                <div class="flex items-center mb-4">
                  <div class="p-2 bg-amber-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-800">Información de la Empresa</h3>
                </div>
                <div class="space-y-2 text-gray-600 pl-11">
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">Empresa:</span> Daylen Cafetería</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">Dirección:</span> Calle Carlos Gómez, Barrio Remansito Sector 5, CDE</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">Teléfono:</span> +595 986 195914</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">Email:</span> daylencoffee@gmail.com</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">RUC:</span> 12345678-9</span>
                  </p>
                </div>
              </div>

              <!-- Información del Cliente -->
              <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                <div class="flex items-center mb-4">
                  <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-800">Información del Cliente</h3>
                </div>
                <div class="space-y-2 text-gray-600 pl-11">
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Cliente:</span> {{ order.user.name }}</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Email:</span> {{ order.user.email }}</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Nombre de Entrega:</span> {{ order.customer_name || 'No especificado' }}</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Teléfono:</span> {{ order.phone || 'No especificado' }}</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Dirección:</span> {{ order.address || 'No especificada' }}</span>
                  </p>
                </div>
              </div>
            </div>

            <!-- Detalles del Pedido -->
            <div class="mb-10">
              <div class="flex items-center mb-4">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                  </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Detalles del Pedido</h3>
              </div>
              
              <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Producto</th>
                      <th class="px-6 py-4 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Cantidad</th>
                      <th class="px-6 py-4 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Precio Unit.</th>
                      <th class="px-6 py-4 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(item, index) in order.order_items" :key="item.id" 
                        :class="{'bg-gray-50': index % 2 === 0, 'hover:bg-gray-100 transition-colors duration-150': true}">
                      <td class="px-6 py-4">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-amber-600">{{ item.product.name.charAt(0) }}</span>
                          </div>
                          <div>
                            <div class="text-sm font-medium text-gray-900">{{ item.product.name }}</div>
                            <div v-if="item.size || item.sugar" class="text-xs text-gray-500 mt-1 space-x-2">
                              <span v-if="item.size" class="inline-flex items-center px-2 py-0.5 rounded bg-amber-100 text-amber-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                                </svg>
                                {{ item.size }}
                              </span>
                              <span v-if="item.sugar" class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                {{ item.sugar }}
                              </span>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700 font-medium">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                          {{ item.quantity }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">
                        ₲ {{ Number(item.price).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                        <span class="font-semibold">₲ {{ Number(item.subtotal).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-6 mb-10 shadow-sm">
              <div class="max-w-md ml-auto">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-amber-200 pb-2">Resumen de la Factura</h3>
                
                <div class="space-y-3 mb-4">
                  <div class="flex justify-between text-gray-700">
                    <span class="font-medium">Subtotal:</span>
                    <span>₲ {{ Number(order.total).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                  </div>
                  
                  <div class="flex justify-between text-gray-700">
                    <span class="font-medium">IVA (10%):</span>
                    <span>₲ {{ Number(order.total * 0.1).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                  </div>
                  
                  <div class="flex justify-between text-gray-700 mb-2">
                    <span class="font-medium">Descuento:</span>
                    <span class="text-green-600">- ₲ 0</span>
                  </div>
                  
                  <div class="border-t border-amber-200 my-3"></div>
                  
                  <div class="flex justify-between text-xl font-bold text-gray-900">
                    <span>Total:</span>
                    <span class="text-2xl text-amber-700">₲ {{ Number(order.total * 1.1).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                  </div>
                </div>
                
                <!-- Estado del Pago -->
                <div class="mt-6 p-4 bg-white rounded-lg border border-green-100">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                      <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-green-800">Pago Completado</h3>
                      <div class="mt-1 text-sm text-green-700">
                        <p>
                          {{ order.payment_method === 'efectivo' ? 'Pago en efectivo' : 'Pago con tarjeta' }}
                          <span v-if="order.payment_id" class="block text-xs text-green-600">ID: {{ order.payment_id }}</span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Notas del Pedido -->
                <div v-if="order.notes" class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                  <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 text-blue-400">
                      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-blue-800">Notas del pedido</h3>
                      <div class="mt-1 text-sm text-blue-700">
                        <p>{{ order.notes }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie de Página -->
            <div class="mt-12 pt-8 border-t border-gray-200">
              <div class="text-center">
                <div class="flex justify-center mb-4">
                  <div class="bg-amber-100 p-3 rounded-full">
                    <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                    </svg>
                  </div>
                </div>
                <h3 class="text-lg font-medium text-gray-900">¡Gracias por su compra!</h3>
                <p class="mt-1 text-gray-600">Su satisfacción es nuestra mayor prioridad</p>
                <p class="mt-4 text-sm text-gray-500">
                  Para consultas, contáctenos en <a href="mailto:daylencoffee@gmail.com" class="text-amber-600 hover:text-amber-700 font-medium">daylencoffee@gmail.com</a>
                </p>
                <p class="mt-2 text-xs text-gray-400">Factura generada el {{ formatDateTime(new Date()) }}</p>
              </div>
            </div>

            <!-- Botón de Descarga PDF -->
            <div class="mt-10">
              <div class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="max-w-md mx-auto">
                  <div class="flex justify-center mb-4">
                    <div class="bg-white p-3 rounded-full shadow-md">
                      <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                      </svg>
                    </div>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-800 mb-2">¿Necesitas tu factura en PDF?</h3>
                  <p class="text-gray-600 mb-6">Guarda una copia digital de tu factura para tus registros</p>
                  <button
                    @click="downloadPDF"
                    class="group relative w-full max-w-xs mx-auto bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 overflow-hidden"
                  >
                    <span class="relative z-10 flex items-center">
                      <svg class="w-5 h-5 mr-2 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      <span>Descargar Factura PDF</span>
                    </span>
                    <span class="absolute inset-0 bg-white/10 group-hover:bg-white/20 transition-colors duration-300"></span>
                  </button>
                  <p class="mt-3 text-xs text-gray-500">Formato: PDF | Tamaño: ~200KB</p>
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
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props
const props = defineProps({
  order: {
    type: Object,
    required: true
  }
})

// Methods
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatDateTime = (date) => {
  const options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: true
  }
  return new Date(date).toLocaleString('es-ES', options)
}

const downloadPDF = async () => {
  try {
    console.log('🎯 Iniciando conversión directa HTML a PDF para pedido:', props.order.id)

    // Verificar que los datos del pedido estén disponibles
    if (!props.order || !props.order.id) {
      throw new Error('No se encontraron los datos del pedido. Por favor, recarga la página.')
    }

    // Mostrar indicador de carga
    const loadingToast = document.createElement('div')
    loadingToast.innerHTML = `
      <div style="
        position: fixed;
        top: 20px;
        right: 20px;
        background: #f59e0b;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 8px;
      ">
        <div style="width: 16px; height: 16px; border: 2px solid white; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        Generando PDF...
      </div>
      <style>
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      </style>
    `
    document.body.appendChild(loadingToast)

    // Función auxiliar para formatear fechas
    const formatDateForPDF = (date) => {
      try {
        if (!date) return 'Fecha no disponible'
        return new Date(date).toLocaleDateString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        })
      } catch (error) {
        console.error('❌ Error formateando fecha:', error)
        return 'Fecha no disponible'
      }
    }

    // Cargar las librerías necesarias
    console.log('📦 Cargando librerías para conversión HTML a PDF...')

    // Cargar html2canvas
    if (typeof window.html2canvas === 'undefined') {
      const canvasScript = document.createElement('script')
      canvasScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js'
      document.head.appendChild(canvasScript)

      await new Promise((resolve, reject) => {
        canvasScript.onload = resolve
        canvasScript.onerror = () => reject(new Error('No se pudo cargar html2canvas'))
        setTimeout(() => reject(new Error('Timeout cargando html2canvas')), 10000)
      })
    }

    // Cargar jsPDF
    if (typeof window.jspdf === 'undefined') {
      const pdfScript = document.createElement('script')
      pdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'
      document.head.appendChild(pdfScript)

      await new Promise((resolve, reject) => {
        pdfScript.onload = resolve
        pdfScript.onerror = () => reject(new Error('No se pudo cargar jsPDF'))
        setTimeout(() => reject(new Error('Timeout cargando jsPDF')), 10000)
      })
    }

    console.log('✅ Librerías cargadas exitosamente')

    // Crear elemento temporal con el contenido del PDF
    console.log('📝 Creando contenido del PDF...')
    const pdfContent = document.createElement('div')
    pdfContent.style.position = 'absolute'
    pdfContent.style.left = '-9999px'
    pdfContent.style.top = '-9999px'
    pdfContent.style.width = '794px' // A4 width in pixels
    pdfContent.style.background = 'white'
    pdfContent.style.fontFamily = 'Arial, sans-serif'

    pdfContent.innerHTML = `
      <div style="font-family: Arial, sans-serif; color: #333; max-width: 794px; margin: 0 auto; background: white; padding: 25px;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); color: white; padding: 15px; text-align: center; margin-bottom: 15px; border-radius: 10px;">
          <h1 style="font-size: 20px; margin: 0; margin-bottom: 6px; font-weight: bold;">☕ Daylen Cafetería</h1>
          <p style="font-size: 12px; margin: 0; opacity: 0.9; margin-bottom: 8px;">Sistema de Facturación Electrónica</p>
          <div style="background: white; color: #d97706; padding: 6px 15px; border-radius: 3px; font-weight: bold; font-size: 14px; display: inline-block;">
            FACTURA N° ${props.order.id.toString().padStart(6, '0')}
          </div>
        </div>

        <!-- Información de la Empresa -->
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información de la Empresa</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Empresa:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">Daylen Cafetería</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">RUC:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">12345678-9</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Dirección:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Teléfono:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">+595 986 195914</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Email:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">daylencoffee@gmail.com</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Fecha:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${formatDateForPDF(new Date())}</td>
            </tr>
          </table>
        </div>

        <!-- Información del Cliente -->
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información del Cliente</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Cliente:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.user?.name || 'Cliente no disponible'}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">Email:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.user?.email || 'Email no disponible'}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Nombre de Entrega:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.customer_name || 'No especificado'}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Teléfono:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.phone || 'No especificado'}</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Dirección:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;" colspan="3">${props.order.address || 'No especificada'}</td>
            </tr>
          </table>
        </div>

        <!-- Detalles del Pedido -->
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Detalles del Pedido</h3>
          <table style="width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; margin-bottom: 10px;">
            <thead>
              <tr style="background: #f9fafb;">
                <th style="padding: 6px; text-align: left; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; font-size: 9px;">Producto</th>
                <th style="padding: 6px; text-align: center; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 60px; font-size: 9px;">Cant.</th>
                <th style="padding: 6px; text-align: right; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 80px; font-size: 9px;">Precio</th>
                <th style="padding: 6px; text-align: right; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; width: 80px; font-size: 9px;">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              ${props.order.order_items && props.order.order_items.length > 0 ? props.order.order_items.map(item => `
                <tr>
                  <td style="padding: 6px; border: 1px solid #e5e7eb; color: #374151; font-size: 9px;">
                    <strong>${item.product?.name || 'Producto eliminado'}</strong>
                    ${item.size || item.sugar ? `<br><small style="color: #6b7280; font-size: 8px;">${item.size || ''} ${item.size && item.sugar ? '•' : ''} ${item.sugar || ''}</small>` : ''}
                  </td>
                  <td style="padding: 6px; text-align: center; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">${item.quantity || 0}</td>
                  <td style="padding: 6px; text-align: right; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">₲ ${Number(item.price || 0).toLocaleString('es-PY')}</td>
                  <td style="padding: 6px; text-align: right; border: 1px solid #e5e7eb; color: #374151; font-size: 9px; font-weight: 500;">₲ ${Number(item.subtotal || 0).toLocaleString('es-PY')}</td>
                </tr>
              `).join('') : '<tr><td colspan="4" style="padding: 12px; text-align: center; color: #999; border: 1px solid #e5e7eb; font-size: 9px;">No hay productos disponibles</td></tr>'}
            </tbody>
          </table>
        </div>

        <!-- Totales -->
        <div style="background: #f9fafb; padding: 12px; margin: 12px 0; border-radius: 4px; border: 1px solid #e5e7eb;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">Subtotal:</span>
            <span style="color: #374151; font-weight: 500;">₲ ${Number(props.order.total || 0).toLocaleString('es-PY')}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">IVA (10%):</span>
            <span style="color: #374151; font-weight: 500;">₲ ${Number((props.order.total || 0) * 0.1).toLocaleString('es-PY')}</span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 11px;">
            <span style="font-weight: bold; color: #6b7280;">Descuento:</span>
            <span style="color: #374151; font-weight: 500;">₲ 0</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; color: #d97706; border-top: 2px solid #d97706; padding-top: 6px; margin-top: 6px;">
            <span>TOTAL:</span>
            <span>₲ ${Number((props.order.total || 0) * 1.1).toLocaleString('es-PY')}</span>
          </div>
        </div>

        <!-- Información de Pago -->
        <div style="margin-bottom: 12px;">
          <h3 style="font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 8px; border-bottom: 2px solid #d97706; padding-bottom: 4px;">Información de Pago</h3>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 120px; vertical-align: top; font-size: 10px;">Método de Pago:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.payment_method === 'efectivo' ? 'Efectivo' : 'Tarjeta'}</td>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; width: 100px; vertical-align: top; font-size: 10px;">Estado:</td>
              <td style="padding: 3px 0; color: #059669; font-weight: bold; font-size: 10px;">Pagado</td>
            </tr>
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Fecha del Pedido:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${formatDateForPDF(props.order.created_at)}</td>
              ${props.order.payment_id ? `<td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">ID de Pago:</td><td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;">${props.order.payment_id}</td>` : '<td></td><td></td>'}
            </tr>
            ${props.order.notes ? `
            <tr>
              <td style="padding: 3px 0; font-weight: bold; color: #6b7280; vertical-align: top; font-size: 10px;">Notas:</td>
              <td style="padding: 3px 0; color: #374151; font-weight: 500; font-size: 10px;" colspan="3">${props.order.notes}</td>
            </tr>
            ` : ''}
          </table>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; color: #6b7280;">
          <p style="font-size: 12px; font-weight: bold; color: #d97706; margin-bottom: 4px;">¡Gracias por su compra en Daylen Cafetería!</p>
          <p style="font-size: 9px; margin-bottom: 3px;">Para consultas, contáctenos en daylencoffee@gmail.com</p>
          <p style="font-size: 8px; margin-bottom: 3px;">Teléfono: +595 986 195914</p>
          <p style="font-size: 7px; margin-top: 8px; color: #9ca3af;">Factura generada automáticamente el ${formatDateForPDF(new Date())}</p>
        </div>
      </div>
    `

    document.body.appendChild(pdfContent)

    // Esperar a que el contenido se renderice
    await new Promise(resolve => setTimeout(resolve, 1000))

    console.log('📸 Capturando contenido con html2canvas...')

    // Capturar el contenido con html2canvas
    const canvas = await window.html2canvas(pdfContent, {
      scale: 2.5, // Escala equilibrada para buena calidad
      useCORS: true,
      allowTaint: true,
      backgroundColor: '#ffffff',
      width: 794, // A4 width exacto
      height: 1123 // A4 height exacto
    })

    console.log('✅ Contenido capturado exitosamente')

    // Crear PDF con jsPDF en formato A4 optimizado
    console.log('📄 Creando PDF en formato A4 optimizado con jsPDF...')
    const { jsPDF } = window.jspdf
    const pdf = new jsPDF({
      orientation: 'portrait',
      unit: 'mm',
      format: 'a4',
      compress: true // Comprimir para menor tamaño
    })

    // Calcular dimensiones exactas para A4
    const imgWidth = 210 // A4 width exacto en mm
    const pageHeight = 297 // A4 height exacto en mm
    const imgHeight = (canvas.height * imgWidth) / canvas.width
    let heightLeft = imgHeight

    // Agregar primera página
    const imgData = canvas.toDataURL('image/png', 0.95) // Calidad optimizada
    pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight)
    heightLeft -= pageHeight

    // Agregar páginas adicionales si es necesario (aunque debería caber todo)
    while (heightLeft >= 0) {
      pdf.addPage()
      pdf.addImage(imgData, 'PNG', 0, -pageHeight + heightLeft, imgWidth, imgHeight)
      heightLeft -= pageHeight
    }

    console.log('✅ PDF en formato A4 optimizado creado exitosamente')

    // Descargar el PDF
    console.log('⬇️ Descargando PDF...')
    pdf.save(`factura-${props.order.id}.pdf`)

    // Remover el elemento temporal
    document.body.removeChild(pdfContent)

    // Remover indicador de carga
    document.body.removeChild(loadingToast)

    console.log('✅ PDF descargado exitosamente')

  } catch (error) {
    console.error('❌ Error generando PDF:', error)

    // Remover indicador de carga si existe
    const loadingToast = document.querySelector('[style*="Generando PDF"]')
    if (loadingToast) {
      document.body.removeChild(loadingToast.parentElement)
    }
  }
}
</script>

<style>
/* Estilos adicionales para la factura */
.invoice-table {
  min-width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.invoice-table thead {
  background-color: #f9fafb;
}

.invoice-table th {
  padding: 12px 24px;
  text-align: left;
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 1px solid #e5e7eb;
}

.invoice-table td {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e7eb;
  color: #374151;
}

.invoice-total {
  display: flex;
  justify-content: space-between;
  font-size: 18px;
  font-weight: bold;
  color: #374151;
}
</style>
