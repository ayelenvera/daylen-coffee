<template>
  <Head title="Gestión de Promociones" />
  
  <AdminLayout>
    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header Mejorado -->
        <div class="mb-8">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
              <div class="bg-gradient-to-r from-orange-500 to-red-500 p-3 rounded-xl">
                <span class="text-2xl text-white">🔥</span>
              </div>
              <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Promociones</h1>
                <p class="text-gray-600 mt-1">
                  Administra descuentos especiales y ofertas de tus productos
                </p>
              </div>
            </div>
            
            <!-- Estadísticas rápidas MEJORADAS -->
            <div class="flex space-x-4 mt-4 md:mt-0">
              <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl px-4 py-3 shadow-lg">
                <div class="font-bold text-xl">{{ productsWithPromotions.length }}</div>
                <div class="text-amber-100 text-sm">En Promoción</div>
              </div>
              <div class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl px-4 py-3 shadow-lg">
                <div class="font-bold text-xl">{{ productsWithoutPromotions.length }}</div>
                <div class="text-blue-100 text-sm">Disponibles</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Alertas MEJORADAS -->
        <div v-if="successMessage" class="mb-6 p-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg shadow-lg">
          <div class="flex items-center">
            <span class="text-xl mr-3">✅</span>
            <span class="font-medium">{{ successMessage }}</span>
          </div>
        </div>

        <div v-if="errorMessage" class="mb-6 p-4 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg shadow-lg">
          <div class="flex items-center">
            <span class="text-xl mr-3">❌</span>
            <span class="font-medium">{{ errorMessage }}</span>
          </div>
        </div>

        <!-- Pestañas principales MEJORADAS -->
        <div class="mb-6">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-1">
            <nav class="flex space-x-1">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'flex-1 py-3 px-4 text-sm font-medium rounded-md transition-all duration-200 flex items-center justify-center',
                  activeTab === tab.id
                    ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg'
                    : 'text-gray-600 hover:text-amber-600 hover:bg-amber-50'
                ]"
              >
                <span class="mr-2">{{ getTabIcon(tab.id) }}</span>
                {{ tab.name }}
                <span 
                  v-if="tab.count !== null && tab.count > 0"
                  :class="[
                    'ml-2 py-0.5 px-2 text-xs rounded-full font-bold',
                    activeTab === tab.id 
                      ? 'bg-white text-amber-600' 
                      : 'bg-amber-100 text-amber-800'
                  ]"
                >
                  {{ tab.count }}
                </span>
              </button>
            </nav>
          </div>
        </div>

        <!-- Contenido de pestañas -->
        <div class="space-y-8">
          
          <!-- Pestaña: Agregar Promociones MEJORADA -->
          <div v-if="activeTab === 'add'">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
              <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-6 border-b border-amber-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                  <span class="text-amber-600 mr-3">➕</span>
                  Agregar Nueva Promoción
                </h2>
                <p class="text-amber-700 text-sm mt-1">
                  Selecciona un producto y aplica un descuento especial para atraer más clientes
                </p>
              </div>

              <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                  <!-- Selección de producto MEJORADA -->
                  <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                      📦 Seleccionar Producto
                      <span class="text-amber-600 ml-1">*</span>
                    </label>
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                      <div
                        v-for="product in productsWithoutPromotions"
                        :key="product.id"
                        @click="selectProduct(product)"
                        :class="[
                          'p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 transform hover:scale-[1.02]',
                          selectedProduct?.id === product.id
                            ? 'border-amber-500 bg-gradient-to-r from-amber-50 to-orange-50 ring-4 ring-amber-200 shadow-lg'
                            : 'border-gray-200 bg-white hover:border-amber-300 hover:shadow-md'
                        ]"
                      >
                        <div class="flex items-start space-x-4">
                          <img
                            :src="product.image_url"
                            :alt="product.name"
                            class="w-20 h-20 object-cover rounded-lg flex-shrink-0 border border-gray-200"
                          />
                          <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-lg mb-1">{{ product.name }}</h3>
                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ product.description }}</p>
                            <div class="flex items-center justify-between">
                              <span class="text-xl font-bold text-amber-600">
                                ₲ {{ product.price.toLocaleString('es-PY') }}
                              </span>
                              <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                {{ product.category_name }}
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Estado vacío MEJORADO -->
                      <div 
                        v-if="productsWithoutPromotions.length === 0"
                        class="text-center py-12 text-gray-400"
                      >
                        <div class="text-6xl mb-4">🎉</div>
                        <p class="text-lg font-semibold text-gray-600 mb-2">¡Todos los productos tienen promociones!</p>
                        <p class="text-sm">Gestiona las promociones existentes en la pestaña "Promociones Activas"</p>
                      </div>
                    </div>
                  </div>

                  <!-- Configuración de descuento MEJORADA -->
                  <div v-if="selectedProduct" class="space-y-6">
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-5 border border-gray-200">
                      <div class="flex items-center space-x-4">
                        <img
                          :src="selectedProduct.image_url"
                          :alt="selectedProduct.name"
                          class="w-16 h-16 object-cover rounded-xl border-2 border-amber-300"
                        />
                        <div>
                          <h3 class="font-bold text-gray-900 text-lg">{{ selectedProduct.name }}</h3>
                          <p class="text-sm text-gray-600">{{ selectedProduct.category_name }}</p>
                          <p class="text-xl font-bold text-amber-600 mt-1">
                            ₲ {{ selectedProduct.price.toLocaleString('es-PY') }}
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- Selector de descuento MEJORADO -->
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-4">
                        🏷️ Porcentaje de Descuento
                        <span class="text-amber-600 ml-1">*</span>
                      </label>
                      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <button
                          v-for="option in discountOptions"
                          :key="option.value"
                          @click="selectedDiscount = option.value"
                          :class="[
                            'p-4 border-2 rounded-xl text-center transition-all duration-200 transform hover:scale-105',
                            selectedDiscount === option.value
                              ? 'border-amber-500 bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg ring-4 ring-amber-200'
                              : 'border-gray-200 bg-white text-gray-700 hover:border-amber-300 hover:shadow-md'
                          ]"
                        >
                          <div class="text-xl font-bold">{{ option.value }}%</div>
                          <div class="text-xs opacity-90 mt-1">{{ option.label }}</div>
                        </button>
                      </div>
                    </div>

                    <!-- Vista previa del precio MEJORADA -->
                    <div class="bg-white border-2 border-amber-200 rounded-xl p-5 shadow-lg">
                      <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="text-amber-600 mr-2">👁️</span>
                        Vista Previa del Precio
                      </h4>
                      <div class="space-y-3">
                        <div class="flex justify-between items-center">
                          <span class="text-gray-600">Precio original:</span>
                          <span class="text-gray-900 font-semibold text-lg">
                            ₲ {{ selectedProduct.price.toLocaleString('es-PY') }}
                          </span>
                        </div>
                        <div class="flex justify-between items-center">
                          <span class="text-gray-600">Descuento ({{ selectedDiscount }}%):</span>
                          <span class="text-red-600 font-semibold">
                            -₲ {{ discountAmount.toLocaleString('es-PY') }}
                          </span>
                        </div>
                        <div class="border-t border-amber-200 pt-3 mt-3">
                          <div class="flex justify-between items-center">
                            <span class="text-gray-900 font-bold text-lg">Precio promocional:</span>
                            <span class="text-green-600 font-bold text-2xl">
                              ₲ {{ promotionalPrice.toLocaleString('es-PY') }}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Fechas opcionales MEJORADAS -->
                    <div class="space-y-4">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <label class="block text-sm font-semibold text-gray-700 mb-2">
                            🕐 Fecha de inicio (opcional)
                          </label>
                          <input
                            v-model="startDate"
                            type="datetime-local"
                            class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200"
                          />
                        </div>
                        <div>
                          <label class="block text-sm font-semibold text-gray-700 mb-2">
                            🕐 Fecha de fin (opcional)
                          </label>
                          <input
                            v-model="endDate"
                            type="datetime-local"
                            class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200"
                          />
                        </div>
                      </div>
                    </div>

                    <!-- Botón de acción MEJORADO -->
                    <button
                      @click="addPromotion"
                      :disabled="addingPromotion"
                      class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] disabled:scale-100 shadow-lg hover:shadow-xl disabled:shadow-none flex items-center justify-center"
                    >
                      <span v-if="addingPromotion" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Aplicando promoción...
                      </span>
                      <span v-else class="flex items-center text-lg">
                        <span class="mr-3 text-xl">🔥</span>
                        Aplicar {{ selectedDiscount }}% de Descuento
                      </span>
                    </button>
                  </div>

                  <!-- Estado sin producto seleccionado MEJORADO -->
                  <div v-else class="flex items-center justify-center h-64 text-gray-400">
                    <div class="text-center">
                      <div class="text-6xl mb-4">👆</div>
                      <p class="text-lg font-semibold text-gray-600">Selecciona un producto</p>
                      <p class="text-sm mt-1">Elige un producto de la lista para configurar la promoción</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pestaña: Promociones Activas MEJORADA -->
          <div v-if="activeTab === 'active'">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
              <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 border-b border-green-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                  <span class="text-green-600 mr-3">🔥</span>
                  Promociones Activas
                  <span class="ml-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                    {{ productsWithPromotions.length }} activas
                  </span>
                </h2>
              </div>

              <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div
                    v-for="product in productsWithPromotions"
                    :key="product.id"
                    class="border-2 border-gray-200 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] bg-white"
                  >
                    <!-- Badge de descuento MEJORADO -->
                    <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-4 py-3 text-sm font-bold text-center relative">
                      <span class="text-lg">🏷️</span>
                      <span class="ml-2">{{ product.discount_percentage }}% OFF</span>
                      <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-pink-500 rotate-45"></div>
                    </div>

                    <div class="p-5">
                      <!-- Imagen y info del producto MEJORADA -->
                      <div class="flex items-start space-x-4 mb-4">
                        <img
                          :src="product.image_url"
                          :alt="product.name"
                          class="w-20 h-20 object-cover rounded-xl border-2 border-amber-300 flex-shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                          <h3 class="font-bold text-gray-900 text-lg mb-1">{{ product.name }}</h3>
                          <p class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full inline-block">
                            {{ product.category_name }}
                          </p>
                        </div>
                      </div>

                      <!-- Precios MEJORADOS -->
                      <div class="space-y-3 mb-5">
                        <div class="flex justify-between items-center">
                          <span class="text-sm text-gray-600">Precio original:</span>
                          <span class="text-sm text-gray-500 line-through font-semibold">
                            ₲ {{ product.price.toLocaleString('es-PY') }}
                          </span>
                        </div>
                        <div class="flex justify-between items-center">
                          <span class="text-sm text-gray-600">Precio promocional:</span>
                          <span class="text-xl font-bold text-green-600">
                            ₲ {{ product.promotional_price.toLocaleString('es-PY') }}
                          </span>
                        </div>
                        <div class="flex justify-between items-center text-sm bg-green-50 p-2 rounded-lg">
                          <span class="text-gray-700 font-semibold">Ahorro:</span>
                          <span class="text-red-600 font-bold">
                            -₲ {{ product.savings_amount.toLocaleString('es-PY') }}
                          </span>
                        </div>
                      </div>

                      <!-- Fechas MEJORADAS -->
                      <div v-if="product.promotion_data" class="text-xs text-gray-600 space-y-2 mb-5 bg-gray-50 p-3 rounded-lg">
                        <div v-if="product.promotion_data.start_date" class="flex items-center">
                          <span class="text-amber-600 mr-2">📅</span>
                          <span class="font-medium">Inicio:</span> 
                          <span class="ml-1">{{ formatDate(product.promotion_data.start_date) }}</span>
                        </div>
                        <div v-if="product.promotion_data.end_date" class="flex items-center">
                          <span class="text-amber-600 mr-2">⏰</span>
                          <span class="font-medium">Fin:</span> 
                          <span class="ml-1">{{ formatDate(product.promotion_data.end_date) }}</span>
                        </div>
                      </div>

                      <!-- Acciones MEJORADAS -->
                      <div class="flex space-x-3">
                        <button
                          @click="editPromotion(product)"
                          class="flex-1 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white py-3 px-4 rounded-xl text-sm font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                        >
                          <span class="mr-2">✏️</span>
                          Editar
                        </button>
                        <button
                          @click="removePromotion(product)"
                          class="flex-1 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white py-3 px-4 rounded-xl text-sm font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
                        >
                          <span class="mr-2">🗑️</span>
                          Eliminar
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Estado vacío MEJORADO -->
                  <div 
                    v-if="productsWithPromotions.length === 0"
                    class="col-span-full text-center py-16 text-gray-400"
                  >
                    <div class="text-8xl mb-6">😴</div>
                    <p class="text-2xl font-bold text-gray-600 mb-3">No hay promociones activas</p>
                    <p class="text-gray-500">Agrega tu primera promoción en la pestaña "Agregar Promoción"</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pestaña: Historial MEJORADA -->
          <div v-if="activeTab === 'history'">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
              <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-6 border-b border-purple-200">
                <div class="flex items-center justify-between">
                  <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="text-purple-600 mr-3">📊</span>
                    Historial de Promociones
                  </h2>
                  <button
                    @click="clearHistory"
                    :disabled="clearingHistory"
                    class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 flex items-center"
                  >
                    <span v-if="clearingHistory" class="flex items-center">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Limpiando...
                    </span>
                    <span v-else class="flex items-center">
                      <span class="mr-2">🧹</span>
                      Limpiar Historial
                    </span>
                  </button>
                </div>
              </div>

              <div class="p-6">
                <div class="overflow-x-auto rounded-xl shadow-inner">
                  <table class="w-full">
                    <thead>
                      <tr class="bg-gradient-to-r from-gray-50 to-blue-50">
                        <th class="text-left py-4 px-6 text-sm font-bold text-gray-700 border-b border-gray-200">Producto</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-gray-700 border-b border-gray-200">Descuento</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-gray-700 border-b border-gray-200">Precios</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-gray-700 border-b border-gray-200">Fechas</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-gray-700 border-b border-gray-200">Estado</th>
                        <th class="text-left py-4 px-6 text-sm font-bold text-gray-700 border-b border-gray-200">Acciones</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                      <tr v-for="promotion in allPromotions" :key="promotion.id" class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="py-4 px-6">
                          <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-amber-100 to-orange-100 rounded-xl flex items-center justify-center shadow-sm">
                              <span class="text-xl">{{ getCategoryEmoji(promotion.category_name) }}</span>
                            </div>
                            <div>
                              <div class="font-semibold text-gray-900">{{ promotion.product_name }}</div>
                              <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full mt-1">
                                {{ promotion.category_name }}
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="py-4 px-6">
                          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-red-100 to-pink-100 text-red-800 shadow-sm">
                            🏷️ {{ promotion.discount_percentage }}% OFF
                          </span>
                        </td>
                        <td class="py-4 px-6">
                          <div class="space-y-1">
                            <div class="text-sm text-gray-500 line-through">
                              ₲ {{ promotion.original_price.toLocaleString('es-PY') }}
                            </div>
                            <div class="text-lg font-bold text-green-600">
                              ₲ {{ promotion.promotional_price.toLocaleString('es-PY') }}
                            </div>
                          </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-600">
                          <div v-if="promotion.start_date" class="flex items-center mb-1">
                            <span class="text-amber-600 mr-2">📅</span>
                            {{ formatDate(promotion.start_date) }}
                          </div>
                          <div v-if="promotion.end_date" class="flex items-center text-xs text-gray-500">
                            <span class="text-amber-600 mr-2">⏰</span>
                            hasta {{ formatDate(promotion.end_date) }}
                          </div>
                          <div v-else class="text-xs text-gray-400">Sin fecha de fin</div>
                        </td>
                        <td class="py-4 px-6">
                          <span 
                            :class="[
                              'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm',
                              promotion.is_currently_active 
                                ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800' 
                                : 'bg-gradient-to-r from-gray-100 to-blue-100 text-gray-800'
                            ]"
                          >
                            {{ promotion.is_currently_active ? '🟢 Activa' : '⚫ Inactiva' }}
                          </span>
                        </td>
                        <td class="py-4 px-6">
                          <button
                            @click="deletePromotionFromHistory(promotion)"
                            class="text-red-600 hover:text-red-800 transition-colors duration-200 p-2 rounded-lg hover:bg-red-50"
                            title="Eliminar del historial"
                          >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <!-- Estado vacío MEJORADO -->
                  <div 
                    v-if="allPromotions.length === 0"
                    class="text-center py-16 text-gray-400"
                  >
                    <div class="text-8xl mb-6">📝</div>
                    <p class="text-2xl font-bold text-gray-600 mb-3">No hay historial de promociones</p>
                    <p class="text-gray-500">Las promociones que crees aparecerán aquí</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de edición MEJORADO -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-100">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6 rounded-t-2xl">
          <h3 class="text-xl font-bold text-white flex items-center">
            <span class="text-2xl mr-3">✏️</span>
            Editar Promoción
          </h3>
          <p class="text-blue-100 mt-1">Modifica los detalles de la promoción seleccionada</p>
        </div>
        
        <div class="p-6">
          <div v-if="editingProduct" class="space-y-6">
            <!-- Información del producto -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-200">
              <div class="flex items-center space-x-4">
                <img
                  :src="editingProduct.image_url"
                  :alt="editingProduct.name"
                  class="w-16 h-16 object-cover rounded-xl border-2 border-amber-300"
                />
                <div>
                  <h4 class="font-bold text-gray-900 text-lg">{{ editingProduct.name }}</h4>
                  <p class="text-sm text-gray-600">{{ editingProduct.category_name }}</p>
                </div>
              </div>
            </div>

            <!-- Formulario de edición -->
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Porcentaje de Descuento
                </label>
                <select 
                  v-model="editForm.discount_percentage"
                  class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option v-for="option in discountOptions" :key="option.value" :value="option.value">
                    {{ option.value }}% - {{ option.label }}
                  </option>
                </select>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha de inicio
                  </label>
                  <input
                    v-model="editForm.start_date"
                    type="datetime-local"
                    class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha de fin
                  </label>
                  <input
                    v-model="editForm.end_date"
                    type="datetime-local"
                    class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
              </div>

              <div>
                <label class="flex items-center space-x-3">
                  <input
                    v-model="editForm.is_active"
                    type="checkbox"
                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                  />
                  <span class="text-sm font-semibold text-gray-700">Promoción activa</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        
        <div class="p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex justify-end space-x-4">
          <button 
            @click="showEditModal = false"
            class="px-6 py-3 text-gray-600 hover:text-gray-800 font-semibold rounded-xl transition-colors duration-200 hover:bg-gray-200"
          >
            Cancelar
          </button>
          <button 
            @click="savePromotionEdit"
            :disabled="savingEdit"
            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 disabled:scale-100 shadow-lg hover:shadow-xl flex items-center"
          >
            <span v-if="savingEdit" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Guardando...
            </span>
            <span v-else>💾 Guardar Cambios</span>
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

// Props
const props = defineProps({
  productsWithPromotions: {
    type: Array,
    default: () => []
  },
  productsWithoutPromotions: {
    type: Array,
    default: () => []
  },
  allPromotions: {
    type: Array,
    default: () => []
  },
  discountOptions: {
    type: Array,
    default: () => []
  }
})

// Estado reactivo MEJORADO
const activeTab = ref('add')
const selectedProduct = ref(null)
const selectedDiscount = ref(20)
const startDate = ref('')
const endDate = ref('')
const addingPromotion = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const showEditModal = ref(false)
const editingProduct = ref(null)
const savingEdit = ref(false)
const clearingHistory = ref(false)

// Formulario de edición
const editForm = reactive({
  discount_percentage: 20,
  start_date: '',
  end_date: '',
  is_active: true
})

// Tabs con contadores MEJORADOS
const tabs = computed(() => [
  { id: 'add', name: 'Agregar Promoción', count: props.productsWithoutPromotions.length },
  { id: 'active', name: 'Promociones Activas', count: props.productsWithPromotions.length },
  { id: 'history', name: 'Historial', count: props.allPromotions.length }
])

// Cálculos computados
const discountAmount = computed(() => {
  if (!selectedProduct.value) return 0
  return (selectedProduct.value.price * selectedDiscount.value) / 100
})

const promotionalPrice = computed(() => {
  if (!selectedProduct.value) return 0
  return selectedProduct.value.price - discountAmount.value
})

// Métodos MEJORADOS
const getTabIcon = (tabId) => {
  switch(tabId) {
    case 'add': return '➕'
    case 'active': return '🔥'
    case 'history': return '📊'
    default: return '📦'
  }
}

const getCategoryEmoji = (categoryName) => {
  const emojiMap = {
    'Café': '☕',
    'Té': '🍵',
    'Postres': '🍰',
    'Desayunos': '🥐',
    'Ensaladas': '🥗',
    'Bebidas': '🧃',
    'Snacks': '🍫',
    'Panadería': '🥖'
  }
  return emojiMap[categoryName] || '📦'
}

const selectProduct = (product) => {
  selectedProduct.value = product
}

const addPromotion = async () => {
  if (!selectedProduct.value) return

  addingPromotion.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    const response = await fetch('/admin/promotions/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        product_id: selectedProduct.value.id,
        discount_percentage: selectedDiscount.value,
        start_date: startDate.value || null,
        end_date: endDate.value || null
      })
    })

    const result = await response.json()

    if (result.success) {
      successMessage.value = `✅ Promoción aplicada exitosamente a ${selectedProduct.value.name}`
      // Recargar la página para actualizar los datos
      router.reload({ only: ['productsWithPromotions', 'productsWithoutPromotions', 'allPromotions'] })
      
      // Resetear el formulario
      selectedProduct.value = null
      startDate.value = ''
      endDate.value = ''
    } else {
      errorMessage.value = result.message
    }
  } catch (error) {
    errorMessage.value = 'Error al aplicar la promoción. Por favor, intenta nuevamente.'
    console.error('Error:', error)
  } finally {
    addingPromotion.value = false
  }
}

const editPromotion = (product) => {
  editingProduct.value = product
  // Llenar el formulario con los datos actuales
  if (product.promotion_data) {
    editForm.discount_percentage = product.promotion_data.discount_percentage
    editForm.start_date = product.promotion_data.start_date ? 
      new Date(product.promotion_data.start_date).toISOString().slice(0, 16) : ''
    editForm.end_date = product.promotion_data.end_date ? 
      new Date(product.promotion_data.end_date).toISOString().slice(0, 16) : ''
    editForm.is_active = product.promotion_data.is_active
  }
  showEditModal.value = true
}

const savePromotionEdit = async () => {
  if (!editingProduct.value || !editingProduct.value.promotion_data) return

  savingEdit.value = true

  try {
    const response = await fetch(`/admin/promotions/${editingProduct.value.promotion_data.id}/update`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(editForm)
    })

    const result = await response.json()

    if (result.success) {
      successMessage.value = '✅ Promoción actualizada exitosamente'
      showEditModal.value = false
      router.reload({ only: ['productsWithPromotions', 'productsWithoutPromotions', 'allPromotions'] })
    } else {
      errorMessage.value = result.message
    }
  } catch (error) {
    errorMessage.value = 'Error al actualizar la promoción. Por favor, intenta nuevamente.'
    console.error('Error:', error)
  } finally {
    savingEdit.value = false
  }
}

const removePromotion = async (product) => {
  if (!confirm(`¿Estás seguro de que quieres eliminar la promoción de "${product.name}"?`)) {
    return
  }

  try {
    const response = await fetch(`/admin/promotions/${product.promotion_data.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })

    const result = await response.json()

    if (result.success) {
      successMessage.value = `✅ Promoción eliminada exitosamente de ${product.name}`
      router.reload({ only: ['productsWithPromotions', 'productsWithoutPromotions', 'allPromotions'] })
    } else {
      errorMessage.value = result.message
    }
  } catch (error) {
    errorMessage.value = 'Error al eliminar la promoción. Por favor, intenta nuevamente.'
    console.error('Error:', error)
  }
}

const deletePromotionFromHistory = async (promotion) => {
  if (!confirm(`¿Estás seguro de que quieres eliminar esta promoción del historial?`)) {
    return
  }

  try {
    const response = await fetch(`/admin/promotions/${promotion.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })

    const result = await response.json()

    if (result.success) {
      successMessage.value = '✅ Promoción eliminada del historial'
      router.reload({ only: ['productsWithPromotions', 'productsWithoutPromotions', 'allPromotions'] })
    } else {
      errorMessage.value = result.message
    }
  } catch (error) {
    errorMessage.value = 'Error al eliminar la promoción del historial. Por favor, intenta nuevamente.'
    console.error('Error:', error)
  }
}

const clearHistory = async () => {
  if (!confirm('¿Estás seguro de que quieres limpiar todo el historial de promociones? Esta acción no se puede deshacer.')) {
    return
  }

  clearingHistory.value = true

  try {
    // Eliminar todas las promociones inactivas
    const inactivePromotions = props.allPromotions.filter(p => !p.is_currently_active)
    
    for (const promotion of inactivePromotions) {
      await fetch(`/admin/promotions/${promotion.id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
    }

    successMessage.value = `✅ Historial limpiado exitosamente (${inactivePromotions.length} promociones eliminadas)`
    router.reload({ only: ['productsWithPromotions', 'productsWithoutPromotions', 'allPromotions'] })
  } catch (error) {
    errorMessage.value = 'Error al limpiar el historial. Por favor, intenta nuevamente.'
    console.error('Error:', error)
  } finally {
    clearingHistory.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Auto-ocultar mensajes después de 5 segundos
onMounted(() => {
  setInterval(() => {
    if (successMessage.value) successMessage.value = ''
    if (errorMessage.value) errorMessage.value = ''
  }, 5000)
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  /*-webkit-line-clamp: 2;*/
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Animaciones personalizadas */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>