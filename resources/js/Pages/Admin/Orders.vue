<template>
  <AdminLayout>
    <Head title="Gestión de Pedidos" />
    
    <div class="p-6">
      <!-- Botón Deshacer Overlay (mantener igual) -->
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

      <!-- Botones de acción (mantener igual) -->
      <div class="mb-6 flex flex-wrap justify-center gap-3">
        <!-- Eliminar Seleccionados -->
        <button 
          v-if="selectedOrders.length >= 2" 
          @click="deleteSelectedOrders" 
          :disabled="deletingSelected"
          class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          <span>{{ deletingSelected ? 'Eliminando...' : `Eliminar Seleccionados (${selectedOrders.length})` }}</span>
        </button>

        <!-- Eliminar Todo -->
        <button 
          v-if="orders.length > 0" 
          @click="deleteAllOrders" 
          :disabled="deletingAll"
          class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
        >
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
        <button @click="loadOrders" class="mt-4 bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded">
          Reintentar
        </button>
      </div>

      <!-- Tabla de pedidos MEJORADA -->
      <div v-else class="bg-white shadow rounded-lg mx-auto max-w-7xl">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 compact-table">
            <thead class="bg-gray-50">
              <tr>
                <th class="w-12 px-4 py-3 text-center">
                  <input 
                    type="checkbox" 
                    :checked="selectedOrders.length === orders.length && orders.length > 0" 
                    @change="toggleSelectAll" 
                    class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500"
                    :disabled="orders.length === 0"
                  >
                </th>
                <th class="px-3 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  ID
                </th>
                <th class="px-3 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[140px]">
                  Cliente
                </th>
                <th class="px-3 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[200px]">
                  Productos
                </th>
                <th class="px-3 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Total
                </th>
                <th class="px-3 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[120px]">
                  Estado
                </th>
                <th class="px-3 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Pago
                </th>
                <th class="px-3 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Fecha
                </th>
                <th class="px-3 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <template v-if="orders.length > 0">
                <tr 
                  v-for="order in orders" 
                  :key="order.id" 
                  :class="[
                    selectedOrders.includes(order.id) ? 'bg-amber-50' : 'hover:bg-gray-50',
                    'transition-colors duration-150'
                  ]"
                >
                  <!-- Checkbox -->
                  <td class="px-4 py-4 text-center">
                    <input 
                      type="checkbox" 
                      :value="order.id" 
                      v-model="selectedOrders" 
                      class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500"
                    >
                  </td>

                  <!-- ID -->
                  <td class="px-3 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                    #{{ order.id }}
                  </td>

                  <!-- Cliente -->
                  <td class="px-3 py-4">
                    <div class="text-sm font-medium text-gray-900 leading-tight">{{ order.customer_name }}</div>
                    <div class="text-sm text-gray-500 truncate max-w-[130px] leading-tight">{{ order.user?.email }}</div>
                    <div class="text-xs text-blue-600 mt-1" v-if="order.phone">
                      📞 {{ order.phone }}
                    </div>
                  </td>

                  <!-- Productos y Personalizaciones -->
                  <td class="px-3 py-4 text-sm text-gray-700">
                    <div class="max-w-[200px]">
                      <!-- Resumen de productos -->
                      <div class="font-medium text-gray-900 mb-1">
                        {{ order.total_items }} item{{ order.total_items !== 1 ? 's' : '' }}
                      </div>
                      
                      <!-- Lista de productos con personalizaciones -->
                      <div class="space-y-1 max-h-20 overflow-y-auto">
                        <div v-for="item in order.order_items" :key="item.id" class="text-xs border-l-2 border-amber-400 pl-2">
                          <div class="font-medium text-gray-800">
                            {{ item.product_name }} × {{ item.quantity }}
                          </div>
                          
                          <!-- Personalizaciones -->
                          <div v-if="hasPersonalizations(item)" class="text-gray-600 mt-1 space-y-0.5">
                            <div v-if="item.size && item.size !== 'Mediano'" class="flex items-center">
                              <span class="w-2 h-2 bg-amber-400 rounded-full mr-1"></span>
                              <span class="text-xs">Tamaño: {{ item.size }}</span>
                            </div>
                            <div v-if="item.sugar && item.sugar !== 'Normal'" class="flex items-center">
                              <span class="w-2 h-2 bg-blue-400 rounded-full mr-1"></span>
                              <span class="text-xs">Azúcar: {{ item.sugar }}</span>
                            </div>
                            <div v-if="item.toppings && item.toppings.length > 0" class="flex items-center">
                              <span class="w-2 h-2 bg-purple-400 rounded-full mr-1"></span>
                              <span class="text-xs">Toppings: {{ item.toppings.join(', ') }}</span>
                            </div>
                            <div v-if="item.addons && item.addons.length > 0" class="flex items-center">
                              <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                              <span class="text-xs">Addons: {{ item.addons.join(', ') }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>

                  <!-- Total -->
                  <td class="px-3 py-4 text-sm text-gray-900 font-semibold whitespace-nowrap">
                    ₲ {{ formatNumber(order.total) }}
                  </td>

                  <!-- Estado (EDITABLE) -->
                  <td class="px-3 py-4">
                    <select
                      @change="updateOrderStatus(order.id, $event.target.value)"
                      :value="order.status"
                      :class="[
                        'text-sm border rounded px-2 py-2 focus:ring-1 focus:outline-none transition-colors w-full',
                        order.status === 'pending' ? 'border-yellow-300 bg-yellow-50 text-yellow-800' :
                        order.status === 'paid' ? 'border-green-300 bg-green-50 text-green-800' :
                        'border-red-300 bg-red-50 text-red-800'
                      ]"
                    >
                      <option value="pending">⏳ Pendiente</option>
                      <option value="paid">✅ Pagado</option>
                      <option value="cancelled">❌ Cancelado</option>
                    </select>
                  </td>

                  <!-- Método de Pago -->
                  <td class="px-3 py-4 text-sm text-gray-700">
                    <div class="flex items-center space-x-1">
                      <span v-if="order.payment_method === 'tarjeta'" class="text-blue-600">
                        💳
                      </span>
                      <span v-else class="text-green-600">
                        💵
                      </span>
                      <span class="capitalize">
                        {{ order.payment_method === 'tarjeta' ? 'Tarjeta' : 'Efectivo' }}
                      </span>
                    </div>
                  </td>

                  <!-- Fecha -->
                  <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                    {{ formatDate(order.created_at) }}
                  </td>

                  <!-- Acciones -->
                  <td class="px-3 py-4">
                    <div class="flex space-x-2 justify-start">
                      <!-- Ver Factura (MODAL) -->
                      <button 
                        @click="viewInvoice(order)" 
                        class="text-green-600 hover:text-green-800 p-1 transition-colors rounded hover:bg-green-50"
                        title="Ver factura"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                      </button>

                      <!-- Eliminar -->
                      <button 
                        @click="deleteOrder(order.id)" 
                        :disabled="deletingId === order.id"
                        class="text-red-600 hover:text-red-800 disabled:text-red-400 p-1 transition-colors rounded hover:bg-red-50"
                        title="Eliminar"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </template>
              <!-- FILA VACÍA CUANDO NO HAY PEDIDOS -->
              <template v-else>
                <tr>
                  <td colspan="9" class="px-3 py-8 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                      <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      <p class="font-medium text-gray-800 mb-2">No hay pedidos registrados</p>
                      <p class="text-gray-600 mb-4">Cuando los clientes realicen pedidos, aparecerán aquí.</p>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal de Factura - Diseño Mejorado -->
      <div v-if="showInvoiceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-start justify-center p-4 z-50 overflow-y-auto">
        <div class="relative w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-hidden my-8 transition-all duration-300 transform hover:scale-[1.005]">
          <!-- Encabezado con gradiente -->
          <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white p-6 relative overflow-hidden">
            <!-- Elementos decorativos -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full -mr-10 -mt-10"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-500/5 rounded-full -ml-32 mb-10"></div>
            
            <div class="relative z-10">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                  <h2 class="text-2xl font-bold flex items-center">
                    <span class="mr-2">☕</span> Factura #{{ selectedInvoiceOrder?.id.toString().padStart(6, '0') }}
                  </h2>
                  <p class="text-amber-100 text-sm mt-1">Daylen Cafetería • Factura Electrónica</p>
                </div>
                
                <div class="flex items-center space-x-3">
                  <button 
                    @click="downloadPDF(selectedInvoiceOrder)"
                    class="group relative inline-flex items-center px-4 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-medium text-white transition-all duration-200 overflow-hidden"
                  >
                    <span class="relative z-10 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      <span>Descargar PDF</span>
                    </span>
                    <span class="absolute inset-0 bg-white/10 group-hover:bg-white/20 transition-colors duration-300"></span>
                  </button>
                  
                  <button 
                    @click="closeInvoiceModal" 
                    class="p-2 text-amber-100 hover:text-white rounded-full hover:bg-white/10 transition-colors duration-200"
                    title="Cerrar"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Cuerpo de la factura -->
          <div class="p-6 md:p-8">
            <!-- Sección de información -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
              <!-- Información de la Empresa -->
              <div class="bg-amber-50 p-5 rounded-xl border border-amber-100">
                <div class="flex items-center mb-3">
                  <div class="p-2 bg-amber-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                  </div>
                  <h3 class="text-base font-semibold text-gray-800">Información de la Empresa</h3>
                </div>
                <div class="space-y-1.5 text-sm text-gray-600 pl-12">
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">Empresa:</span> Daylen Cafetería</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">Dirección:</span> Calle Carlos Gómez, Barrio Remansito</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">Teléfono:</span> +595 986 195914</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-amber-600 mr-2">•</span>
                    <span><span class="font-medium">RUC:</span> 12345678-9</span>
                  </p>
                </div>
              </div>

              <!-- Información del Cliente -->
              <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                <div class="flex items-center mb-3">
                  <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                  <h3 class="text-base font-semibold text-gray-800">Información del Cliente</h3>
                </div>
                <div class="space-y-1.5 text-sm text-gray-600 pl-12">
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Cliente:</span> {{ selectedInvoiceOrder?.user?.name || 'No especificado' }}</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Email:</span> {{ selectedInvoiceOrder?.user?.email || 'No especificado' }}</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Teléfono:</span> {{ selectedInvoiceOrder?.phone || 'No especificado' }}</span>
                  </p>
                  <p class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span><span class="font-medium">Dirección:</span> {{ selectedInvoiceOrder?.address || 'No especificada' }}</span>
                  </p>
                </div>
              </div>
            </div>

            <!-- Detalles del Pedido -->
            <div class="mb-8">
              <div class="flex items-center mb-4">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Detalles del Pedido</h3>
              </div>
              
              <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Producto</th>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Cantidad</th>
                      <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Precio Unit.</th>
                      <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr 
                      v-for="(item, index) in (selectedInvoiceOrder?.order_items || [])" 
                      :key="item.id" 
                      :class="{'bg-gray-50': index % 2 === 0, 'hover:bg-gray-100 transition-colors duration-150': true}"
                    >
                      <td class="px-6 py-4">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-amber-600 text-sm font-medium">{{ (item.product_name || 'P')[0].toUpperCase() }}</span>
                          </div>
                          <div>
                            <div class="text-sm font-medium text-gray-900">{{ item.product_name || 'Producto eliminado' }}</div>
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
                            <!-- AGREGAR TOPPINGS Y ADDONS -->
                            <div v-if="(item.toppings && item.toppings.length > 0) || (item.addons && item.addons.length > 0)" class="text-xs text-gray-500 mt-1">
                              <div v-if="item.toppings && item.toppings.length > 0" class="mt-1">
                                <span class="font-medium text-purple-700">Coberturas:</span> 
                                <span class="text-purple-600">{{ item.toppings.join(', ') }}</span>
                              </div>
                              <div v-if="item.addons && item.addons.length > 0" class="mt-1">
                                <span class="font-medium text-green-700">Agregados:</span> 
                                <span class="text-green-600">{{ item.addons.join(', ') }}</span>
                              </div>
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
                        ₲ {{ formatNumber(item.price) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                        <div>
                          <span class="font-semibold">₲ {{ formatNumber(item.subtotal) }}</span>
                          <div class="text-xs text-gray-400">IVA incluido</div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Resumen de la Factura -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-6 mb-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-amber-200 pb-2">Resumen de la Factura</h3>
              
              <div class="space-y-3 mb-4">
                <div class="flex justify-between text-gray-700">
                  <div>
                    <span class="font-medium">Total (IVA incluido):</span>
                    <p class="text-xs text-gray-500 mt-1">* Los precios ya incluyen el IVA (10%)</p>
                  </div>
                  <span class="text-xl font-bold text-amber-700">₲ {{ formatNumber(selectedInvoiceOrder?.total) }}</span>
                </div>
                
                <div class="border-t border-amber-200 my-2"></div>
                
                <div class="flex justify-between text-xl font-bold text-gray-900">
                  <span>Total a pagar:</span>
                  <span class="text-2xl text-amber-700">₲ {{ formatNumber(selectedInvoiceOrder?.total) }}</span>
                </div>
              </div>
              
              <!-- Estado del Pago -->
              <div :class="[
                'mt-6 p-4 rounded-lg border',
                selectedInvoiceOrder?.status === 'paid' ? 'bg-green-50 border-green-200' :
                selectedInvoiceOrder?.status === 'pending' ? 'bg-yellow-50 border-yellow-200' :
                'bg-red-50 border-red-200'
              ]">
                <div class="flex items-center">
                  <div :class="[
                    'flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center',
                    selectedInvoiceOrder?.status === 'paid' ? 'bg-green-100' :
                    selectedInvoiceOrder?.status === 'pending' ? 'bg-yellow-100' :
                    'bg-red-100'
                  ]">
                    <svg class="h-6 w-6" :class="{
                      'text-green-600': selectedInvoiceOrder?.status === 'paid',
                      'text-yellow-600': selectedInvoiceOrder?.status === 'pending',
                      'text-red-600': selectedInvoiceOrder?.status === 'cancelled'
                    }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path v-if="selectedInvoiceOrder?.status === 'paid'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      <path v-else-if="selectedInvoiceOrder?.status === 'pending'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 :class="[
                      'text-sm font-medium',
                      selectedInvoiceOrder?.status === 'paid' ? 'text-green-800' :
                      selectedInvoiceOrder?.status === 'pending' ? 'text-yellow-800' :
                      'text-red-800'
                    ]">
                      {{ getStatusText(selectedInvoiceOrder?.status) }}
                    </h3>
                    <div :class="[
                      'mt-1 text-sm',
                      selectedInvoiceOrder?.status === 'paid' ? 'text-green-700' :
                      selectedInvoiceOrder?.status === 'pending' ? 'text-yellow-700' :
                      'text-red-700'
                    ]">
                      <p>
                        {{ selectedInvoiceOrder?.payment_method === 'efectivo' ? 'Pago en efectivo' : 'Pago con tarjeta' }}
                        <span v-if="selectedInvoiceOrder?.payment_id" class="block text-xs" :class="{
                          'text-green-600': selectedInvoiceOrder?.status === 'paid',
                          'text-yellow-600': selectedInvoiceOrder?.status === 'pending',
                          'text-red-600': selectedInvoiceOrder?.status === 'cancelled'
                        }">
                          ID: {{ selectedInvoiceOrder.payment_id }}
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Notas del Pedido -->
              <div v-if="selectedInvoiceOrder?.notes" class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <div class="flex items-start">
                  <div class="flex-shrink-0 h-5 w-5 text-blue-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Notas del pedido</h3>
                    <div class="mt-1 text-sm text-blue-700">
                      <p>{{ selectedInvoiceOrder.notes }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Pie de Página -->
            <div class="text-center pt-4 border-t border-gray-200">
              <div class="flex justify-center mb-3">
                <div class="bg-amber-100 p-2 rounded-full">
                  <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                  </svg>
                </div>
              </div>
              <p class="text-sm text-gray-500">
                Factura generada el {{ formatDate(selectedInvoiceOrder?.created_at) }} • 
                <a href="tel:+595986195914" class="text-amber-600 hover:text-amber-700 font-medium">+595 986 195914</a>
              </p>
              <p class="text-xs text-gray-400 mt-1">¡Gracias por elegir Daylen Cafetería!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, defineProps, watch, nextTick } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ 
  orders: { 
    type: Array, 
    default: () => [] 
  } 
})

// Reactive data
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
const showInvoiceModal = ref(false)
const selectedInvoiceOrder = ref(null)

// Watchers
watch(() => props.orders, (val) => {
  orders.value = val || []
  selectedOrders.value = []
  if (!showUndo.value && !undoData.value) {
    if (undoTimeout.value) {
      clearTimeout(undoTimeout.value)
      undoTimeout.value = null
    }
  }
})

// ✅ NUEVA FUNCIÓN: Verificar si un item tiene personalizaciones
const hasPersonalizations = (item) => {
  return (item.size && item.size !== 'Mediano') || 
         (item.sugar && item.sugar !== 'Normal') || 
         (item.toppings && item.toppings.length > 0) || 
         (item.addons && item.addons.length > 0)
}

// CÁLCULOS DE IVA (PRECIO INCLUIDO)
const calculatePriceWithoutIVA = (price) => {
  // Precio sin IVA = Precio total / 1.10
  return Math.round(price / 1.10)
}

const calculateIVA = (price) => {
  // IVA = Precio total - Precio sin IVA
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

// Función para estados dinámicos
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

// Methods
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
  selectedOrders.value = selectedOrders.value.length === orders.value.length 
    ? [] 
    : orders.value.map(o => o.id)
}

const formatNumber = (num) => {
  return Number(num || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 })
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const viewInvoice = (order) => {
  selectedInvoiceOrder.value = order
  showInvoiceModal.value = true
}

const closeInvoiceModal = () => {
  showInvoiceModal.value = false
  selectedInvoiceOrder.value = null
}

const downloadPDF = async (order) => {
  try {
    console.log('🎯 Iniciando descarga de PDF para pedido:', order.id)

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

    // Crear un iframe oculto para la descarga
    const iframe = document.createElement('iframe')
    iframe.style.display = 'none'
    document.body.appendChild(iframe)

    // Usar la ruta del controlador de administración para generar el PDF
    iframe.src = `/admin/orders/${order.id}/invoice?download=1`
    
    // Limpiar después de un tiempo
    setTimeout(() => {
      if (document.body.contains(loadingToast)) {
        document.body.removeChild(loadingToast)
      }
      if (document.body.contains(iframe)) {
        document.body.removeChild(iframe)
      }
    }, 10000) // Limpiar después de 10 segundos por si acaso

  } catch (error) {
    console.error('Error al generar el PDF:', error)
    alert('Ocurrió un error al generar el PDF. Por favor, inténtalo de nuevo.')
  }
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
  } catch (err) {
    error.value = 'Error al actualizar el estado'
    console.error('Error updating order status:', err)
  }
}

const deleteOrder = async (orderId) => {
  if (!confirm('¿Estás seguro de que quieres eliminar este pedido?')) return
  
  deletingId.value = orderId
  try {
    const orderToDelete = orders.value.find(o => o.id === orderId)
    
    // ✅ INCLUIR order_items EN EL undoData
    undoData.value = { 
      type: 'selected', 
      orders: [{
        ...orderToDelete,
        order_items: (orderToDelete.order_items || orderToDelete.orderItems || []).map(item => ({
            ...item,
            // ✅ INCLUIR INFORMACIÓN EXTRA PARA PRODUCTOS ELIMINADOS
            product_name: item.product?.name || item.product_name || 'Producto eliminado',
            product_id: item.product_id,
            price: item.price,
            quantity: item.quantity,
            subtotal: item.subtotal,
            size: item.size,
            sugar: item.sugar
        }))
      }]
    }

    await router.delete(`/admin/orders/${orderId}`, {
      onSuccess: () => { 
        startUndoTimer()
        router.reload({ only: ['orders'] }) 
      },
      onError: () => { 
        error.value = 'Error al eliminar el pedido'
        undoData.value = null 
      }
    })
  } finally { 
    deletingId.value = null 
  }
}

const deleteSelectedOrders = async () => {
  if (selectedOrders.value.length === 0) return
  
  const confirmMessage = `¿Estás seguro de que quieres eliminar ${selectedOrders.value.length} pedido${selectedOrders.value.length !== 1 ? 's' : ''}?`
  if (!confirm(confirmMessage)) return

  deletingSelected.value = true
  try {
    const ordersToDelete = orders.value.filter(o => selectedOrders.value.includes(o.id))
    
    // ✅ INCLUIR order_items EN EL undoData
    undoData.value = { 
      type: 'selected', 
      orders: ordersToDelete.map(order => ({
        ...order,
        order_items: (order.order_items || order.orderItems || []).map(item => ({
            ...item,
            // ✅ INCLUIR INFORMACIÓN EXTRA PARA PRODUCTOS ELIMINADOS
            product_name: item.product?.name || item.product_name || 'Producto eliminado',
            product_id: item.product_id,
            price: item.price,
            quantity: item.quantity,
            subtotal: item.subtotal,
            size: item.size,
            sugar: item.sugar
        }))
      }))
    }

    await router.post('/admin/orders/bulk-delete', { 
      order_ids: selectedOrders.value 
    }, {
      onSuccess: () => { 
        startUndoTimer()
        selectedOrders.value = []
        router.reload({ only: ['orders'] }) 
      },
      onError: () => { 
        error.value = 'Error al eliminar los pedidos seleccionados'
        undoData.value = null 
      }
    })
  } finally { 
    deletingSelected.value = false 
  }
}

const deleteAllOrders = async () => {
  if (!confirm('¿Estás COMPLETAMENTE SEGURO de que quieres eliminar TODOS los pedidos?')) return

  deletingAll.value = true
  try {
    // ✅ INCLUIR order_items EN EL undoData
    undoData.value = { 
      type: 'all', 
      orders: orders.value.map(order => ({
        ...order,
        order_items: (order.order_items || order.orderItems || []).map(item => ({
            ...item,
            // ✅ INCLUIR INFORMACIÓN EXTRA PARA PRODUCTOS ELIMINADOS
            product_name: item.product?.name || item.product_name || 'Producto eliminado',
            product_id: item.product_id,
            price: item.price,
            quantity: item.quantity,
            subtotal: item.subtotal,
            size: item.size,
            sugar: item.sugar
        }))
      }))
    }

    await router.post('/admin/orders/delete-all', {}, {
      onSuccess: () => { 
        startUndoTimer()
        selectedOrders.value = []
        router.reload({ only: ['orders'] }) 
      },
      onError: () => { 
        error.value = 'Error al eliminar todos los pedidos'
        undoData.value = null 
      }
    })
  } finally { 
    deletingAll.value = false 
  }
}

const undoLastAction = async () => {
  if (!undoData.value) return

  showUndo.value = false
  if (undoTimeout.value) {
    clearTimeout(undoTimeout.value)
    undoTimeout.value = null
  }

  try {
    await router.post('/admin/orders/restore', { 
      undo_data: undoData.value 
    }, {
      onSuccess: () => { 
        router.reload({ only: ['orders'] })
        undoData.value = null 
      },
      onError: () => { 
        error.value = 'Error al restaurar los pedidos' 
      }
    })
  } catch (err) {
    console.error('Error restaurando pedidos:', err)
  }
}

const startUndoTimer = () => {
  showUndo.value = true
  undoTimer.value = 5

  nextTick(() => {
    const countdown = () => {
      undoTimer.value--
      if (undoTimer.value > 0) {
        undoTimeout.value = setTimeout(countdown, 1000)
      } else {
        showUndo.value = false
        undoData.value = null
        undoTimeout.value = null
      }
    }
    undoTimeout.value = setTimeout(countdown, 1000)
  })
}

const cancelUndo = () => {
  showUndo.value = false
  if (undoTimeout.value) {
    clearTimeout(undoTimeout.value)
    undoTimeout.value = null
  }
  undoData.value = null
}
</script>

<style scoped>
.compact-table {
  border-spacing: 0;
  border-collapse: separate;
}

.compact-table th {
  padding: 12px 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.compact-table td {
  padding: 16px 12px;
  vertical-align: middle;
}

.compact-table tbody tr {
  height: 64px;
}

.compact-table td {
  vertical-align: middle;
}

.compact-table .w-5.h-5 {
  width: 1.25rem;
  height: 1.25rem;
}

/* Scroll personalizado para la lista de productos */
.max-h-20::-webkit-scrollbar {
  width: 4px;
}

.max-h-20::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 2px;
}

.max-h-20::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 2px;
}

.max-h-20::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}
</style>