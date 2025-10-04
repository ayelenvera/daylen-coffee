<template>
  <Head title="Factura" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Factura del Pedido</h2>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
          <Link :href="route('orders.index')" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 text-center">← Volver a Pedidos</Link>
          <button @click="downloadPDF" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 min-w-[140px] shadow-lg hover:shadow-xl transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>📄 Descargar PDF</span>
          </button>
        </div>
      </div>
    </template>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden" id="invoice-content">
          <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-6 px-8">
            <div class="flex justify-between items-start">
              <div>
                <h1 class="text-2xl font-bold mb-1">☕ Daylen Cafetería</h1>
                <p class="text-amber-100">Sistema de Facturación</p>
                <p class="text-amber-100 text-sm mt-1">Factura Electrónica</p>
              </div>
              <div class="text-right">
                <div class="bg-white text-amber-800 px-3 py-1 rounded-lg font-bold text-lg">FACTURA</div>
                <p class="text-amber-100 text-sm mt-2">N° {{ order.id.toString().padStart(6, '0') }}</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
              <!-- En la sección de Información de la Empresa, reemplazar: -->
              <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de la Empresa</h3>
                <div class="space-y-2 text-gray-600">
                  <p><span class="font-medium">Empresa:</span> {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}</p>
                  <p><span class="font-medium">Dirección:</span> {{ $page.props.shopSettings?.address || 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este' }}</p>
                  <p><span class="font-medium">Teléfono:</span> {{ $page.props.shopSettings?.phone || '+595 986 195914' }}</p>
                  <p><span class="font-medium">Email:</span> {{ $page.props.shopSettings?.email || 'daylencoffee@gmail.com' }}</p>
                  <p><span class="font-medium">RUC:</span> {{ $page.props.shopSettings?.ruc || '12345678-9' }}</p>
                </div>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Cliente</h3>
                <div class="space-y-2 text-gray-600">
                  <p><span class="font-medium">Cliente:</span> {{ order.user.name }}</p>
                  <p><span class="font-medium">Email:</span> {{ order.user.email }}</p>
                  <p><span class="font-medium">Nombre de Entrega:</span> {{ order.customer_name }}</p>
                  <p><span class="font-medium">Teléfono:</span> {{ order.phone }}</p>
                  <p><span class="font-medium">Dirección:</span> {{ order.address }}</p>
                </div>
              </div>
            </div>
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles del Pedido</h3>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unit. (sin IVA)</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IVA Incluido</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in order.order_items" :key="item.id">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div>
                            <div class="text-sm font-medium text-gray-900">{{ item.product.name }}</div>
                            <div v-if="item.size || item.sugar" class="text-sm text-gray-500">
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
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.quantity }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₲ {{ Number(calculatePriceWithoutIVA(item.price)).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">₲ {{ Number(calculateIVA(item.price)).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">₲ {{ Number(item.subtotal).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="border-t border-gray-200 pt-6">
              <div class="flex justify-end">
                <div class="w-64">
                  <div class="flex justify-between text-gray-600 mb-2">
                    <span>Subtotal (sin IVA):</span>
                    <span>₲ {{ Number(calculateSubtotalWithoutIVA(order)).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                  </div>
                  <div class="flex justify-between text-gray-600 mb-2">
                    <span>IVA (10% incluido):</span>
                    <span class="text-blue-600">₲ {{ Number(calculateTotalIVA(order)).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                  </div>
                  <div class="flex justify-between text-gray-600 mb-2">
                    <span>Descuento:</span>
                    <span>₲ 0</span>
                  </div>
                  <div class="border-t border-gray-200 pt-2">
                    <div class="flex justify-between text-lg font-bold text-gray-900">
                      <span>TOTAL (IVA incluido):</span>
                      <span class="text-amber-600">₲ {{ Number(order.total).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de Pago</h3>
                <div class="space-y-2 text-gray-600">
                  <p><span class="font-medium">Método de Pago:</span> {{ order.payment_method === 'efectivo' ? 'Efectivo' : 'Tarjeta' }}</p>
                  <p><span class="font-medium">Estado:</span>
                    <span :class="getStatusBadgeClass(order.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2">{{ getStatusText(order.status) }}</span>
                  </p>
                  <p v-if="order.payment_id"><span class="font-medium">ID de Pago:</span> {{ order.payment_id }}</p>
                </div>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Pedido</h3>
                <div class="space-y-2 text-gray-600">
                  <p><span class="font-medium">Fecha del Pedido:</span> {{ formatDate(order.created_at) }}</p>
                  <p><span class="font-medium">Notas:</span> {{ order.notes || 'Sin notas' }}</p>
                </div>
              </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-200">
              <div class="text-center text-gray-500 text-sm">
                <p>¡Gracias por su compra!</p>
                <p class="mt-1">Para consultas, contáctenos en daylencoffee@gmail.com</p>
                <p class="mt-2 text-xs">Factura generada automáticamente el {{ formatDate(new Date()) }}</p>
              </div>
            </div>
            <div class="mt-8 pt-6 border-t border-gray-200">
              <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold text-red-800 mb-3">¿Necesitas tu factura en PDF?</h3>
                <p class="text-red-600 mb-4">Haz clic en el botón a continuación para descargar tu factura en formato PDF</p>
                <button @click="downloadPDF" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 mx-auto min-w-[200px] text-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                  <span>Descargar Factura PDF</span>
                </button>
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

const props = defineProps({
  order: {
    type: Object,
    required: true
  }
})

const calculatePriceWithoutIVA = (price) => {
  return Math.round(price / 1.10)
}

const calculateIVA = (price) => {
  return price - calculatePriceWithoutIVA(price)
}

const calculateSubtotalWithoutIVA = (order) => {
  if (!order || !order.order_items) return 0
  return order.order_items.reduce((total, item) => {
    return total + (calculatePriceWithoutIVA(item.price || 0) * (item.quantity || 1))
  }, 0)
}

const calculateTotalIVA = (order) => {
  if (!order || !order.order_items) return 0
  return order.order_items.reduce((total, item) => {
    return total + (calculateIVA(item.price || 0) * (item.quantity || 1))
  }, 0)
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'pending': return 'bg-yellow-100 text-yellow-800'
    case 'paid': return 'bg-green-100 text-green-800'
    case 'cancelled': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}

const getStatusText = (status) => {
  switch (status) {
    case 'pending': return 'Pendiente'
    case 'paid': return 'Pagado'
    case 'cancelled': return 'Cancelado'
    default: return status
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const downloadPDF = async () => {
  try {
    console.log('🎯 Iniciando descarga de PDF para pedido:', props.order.id)

    // Mostrar indicador de carga
    const loadingToast = document.createElement('div')
    loadingToast.innerHTML = `
      <div style="
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      ">
        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
        <span>Generando PDF, por favor espere...</span>
      </div>
    `
    document.body.appendChild(loadingToast)

    // ✅ USAR LA NUEVA RUTA PARA DESCARGAR PDF
    const iframe = document.createElement('iframe')
    iframe.style.display = 'none'
    document.body.appendChild(iframe)

    iframe.src = `/orders/${props.order.id}/download-invoice`
    
    // Limpiar después de un tiempo
    setTimeout(() => {
      if (document.body.contains(loadingToast)) {
        document.body.removeChild(loadingToast)
      }
      if (document.body.contains(iframe)) {
        document.body.removeChild(iframe)
      }
    }, 10000)

  } catch (error) {
    console.error('Error al generar el PDF:', error)
    alert('Ocurrió un error al generar el PDF. Por favor, inténtalo de nuevo.')
    
    // Limpiar loading toast en caso de error
    const loadingToast = document.querySelector('[style*="Generando PDF"]')
    if (loadingToast) {
      document.body.removeChild(loadingToast.parentElement)
    }
  }
}
</script>

<style>
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