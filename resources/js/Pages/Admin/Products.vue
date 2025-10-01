<template>
  <Head title="Gestión de Productos" />
  
  <AdminLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Botones principales -->
        <div class="mb-6 flex justify-center space-x-4">
          <!-- Botón Deshacer (aparece temporalmente) -->
          <button
            v-if="showUndo && undoData"
            @click="undoLastAction"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2 animate-pulse"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
            </svg>
            <span>Deshacer ({{ undoTimer }}s)</span>
          </button>

          <!-- Botón Eliminar Seleccionados -->
          <button
            v-if="selectedProducts.length >= 2"
            @click="deleteSelectedProducts"
            :disabled="deletingSelected"
            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>{{ deletingSelected ? 'Eliminando...' : `Eliminar Seleccionados (${selectedProducts.length})` }}</span>
          </button>

          <!-- Botón Eliminar Todo -->
          <button
            v-if="products.length > 0"
            @click="deleteAllProducts"
            :disabled="deletingAll"
            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>{{ deletingAll ? 'Eliminando...' : 'Eliminar Todo' }}</span>
          </button>

          <!-- Botón Agregar Producto -->
          <button
            @click="showCreateModal = true"
            class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Agregar Nuevo Producto</span>
          </button>
        </div>

        <!-- Loading state -->
        <div v-if="loading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
          <p class="mt-2 text-gray-600">Cargando productos...</p>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="text-center py-8">
          <div class="text-red-600 mb-4">
            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <p class="text-red-600 font-medium">{{ error }}</p>
          <button 
            @click="loadProducts" 
            class="mt-4 bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded"
          >
            Reintentar
          </button>
        </div>

        <!-- Products Grid (como el catálogo) -->
        <div v-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="product in products"
            :key="product.id"
            :class="[
              'bg-white border rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300',
              selectedProducts.includes(product.id) ? 'border-amber-500 ring-2 ring-amber-200' : 'border-gray-200'
            ]"
          >
            <!-- Product image with checkbox overlay -->
            <div class="relative aspect-w-16 aspect-h-9 bg-gray-200 rounded-t-lg overflow-hidden">
              <img
                :src="product.image_url"
                :alt="product.name"
                class="w-full h-48 object-cover rounded-t-lg"
                @error="handleImageError($event, product)"
              />

              <!-- Checkbox en esquina superior izquierda -->
              <label class="absolute top-2 left-2 cursor-pointer">
                <input
                  type="checkbox"
                  :value="product.id"
                  v-model="selectedProducts"
                  class="w-5 h-5 text-amber-600 bg-white bg-opacity-80 border-2 border-gray-300 rounded focus:ring-amber-500 focus:ring-2 checked:bg-opacity-90"
                />
              </label>
            </div>

            <!-- Product info -->
            <div class="p-4">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
              <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ product.description || 'Sin descripción' }}</p>

              <div class="flex items-center justify-between mb-4">
                <span class="text-2xl font-bold text-amber-600">₲ {{ product.price.toLocaleString('es-PY') }}</span>
                <span class="text-sm text-gray-500">Stock: {{ product.stock }}</span>
              </div>

              <!-- Action buttons -->
              <div class="flex space-x-2">
                <button
                  @click="editProduct(product)"
                  class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-1"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  <span>Editar</span>
                </button>
                <button
                  @click="deleteProduct(product.id)"
                  :disabled="deletingId === product.id"
                  class="flex-1 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-1"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                  <span>{{ deletingId === product.id ? 'Eliminando...' : 'Eliminar' }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-else class="text-center py-12">
          <div class="text-gray-400 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
          </div>
          <p class="text-gray-600">No hay productos registrados.</p>
        </div>

        <!-- Botones principales (parte inferior) -->
        <div class="mt-6 flex justify-center space-x-4">
          <!-- Botón Deshacer Overlay (fijo en la parte superior) -->
          <div v-if="showUndo && undoData" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50">
            <div class="bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-4 animate-pulse border-2 border-blue-400">
              <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
              </svg>
              <div class="flex items-center space-x-3">
                <span class="font-medium">¿Deshacer eliminación? ({{ undoTimer }}s)</span>
                <button 
                  @click="undoLastAction"
                  class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded font-medium transition-colors"
                >
                  Deshacer
                </button>
                <button 
                  @click="cancelUndo"
                  class="bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded font-medium transition-colors"
                  title="Cancelar"
                >
                  ✕
                </button>
              </div>
            </div>
          </div>

          <!-- Botón Eliminar Seleccionados -->
          <button
            v-if="selectedProducts.length >= 2"
            @click="deleteSelectedProducts"
            :disabled="deletingSelected"
            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>{{ deletingSelected ? 'Eliminando...' : `Eliminar Seleccionados (${selectedProducts.length})` }}</span>
          </button>

          <!-- Botón Eliminar Todo -->
          <button
            v-if="products.length > 0"
            @click="deleteAllProducts"
            :disabled="deletingAll"
            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>{{ deletingAll ? 'Eliminando...' : 'Eliminar Todo' }}</span>
          </button>

          <!-- Botón Agregar Producto -->
          <button
            @click="showCreateModal = true"
            class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-lg flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Agregar Nuevo Producto</span>
          </button>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-gray-600/50 backdrop-blur-sm flex items-start justify-center p-4 z-50 overflow-y-auto">
          <div class="relative w-full max-w-3xl bg-white rounded-xl shadow-2xl overflow-hidden my-8 transition-all duration-300">
            <!-- Encabezado con gradiente -->
            <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white p-6 relative overflow-hidden">
              <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-full -mr-6 -mt-6"></div>
              <div class="relative z-10">
                <h2 class="text-2xl font-bold flex items-center">
                  <span class="mr-2">{{ showEditModal ? '✏️' : '➕' }}</span>
                  {{ showEditModal ? 'Editar Producto' : 'Nuevo Producto' }}
                </h2>
                <p class="text-amber-100 text-sm mt-1">{{ showEditModal ? 'Actualiza los detalles del producto' : 'Completa la información del nuevo producto' }}</p>
              </div>
            </div>
            
            <!-- Cuerpo del formulario -->
            <form @submit.prevent="saveProduct" class="p-6 space-y-5" enctype="multipart/form-data">
              <!-- Nombre -->
              <div class="space-y-1.5">
                <label class="block text-sm font-medium text-gray-700">
                  Nombre <span class="text-red-500">*</span>
                  <span class="text-xs text-gray-500 float-right">{{ form.name ? form.name.length : 0 }}/50</span>
                </label>
                <div class="relative">
                  <input 
                    v-model="form.name"
                    type="text" 
                    maxlength="50"
                    required
                    placeholder="Ej: Café Americano"
                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                  />
                </div>
                <p v-if="form.name && form.name.length > 40" class="text-xs text-amber-600">
                  Quedan {{ 50 - form.name.length }} caracteres
                </p>
              </div>
              
              <!-- Descripción -->
              <div class="space-y-1.5">
                <label class="block text-sm font-medium text-gray-700">
                  Descripción <span class="text-red-500">*</span>
                  <span class="text-xs text-gray-500 float-right">{{ form.description ? form.description.length : 0 }}/200</span>
                </label>
                <textarea 
                  v-model="form.description"
                  rows="3"
                  maxlength="200"
                  required
                  placeholder="Describe el producto en detalle"
                  class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                ></textarea>
                <p v-if="form.description && form.description.length > 180" class="text-xs text-amber-600">
                  Quedan {{ 200 - form.description.length }} caracteres
                </p>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Precio -->
                <div class="space-y-1.5">
                  <label class="block text-sm font-medium text-gray-700">
                    Precio (₲) <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span class="text-gray-500">₲</span>
                    </div>
                    <input 
                      v-model="form.price"
                      type="number" 
                      min="1000"
                      max="100000"
                      step="100"
                      required
                      class="block w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                      @blur="validatePrice"
                    />
                  </div>
                  <p class="text-xs text-gray-500">Mín: ₲ 1,000 - Máx: ₲ 100,000</p>
                </div>
                
                <!-- Stock -->
                <div class="space-y-1.5">
                  <label class="block text-sm font-medium text-gray-700">
                    Stock <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model.number="form.stock"
                    type="number" 
                    min="0"
                    max="1000"
                    required
                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                  />
                  <p class="text-xs text-gray-500">Mín: 0 - Máx: 1,000 unidades</p>
                </div>
              </div>
              
              <!-- Categoría -->
              <div class="space-y-1.5">
                <div class="flex justify-between items-center">
                  <label class="block text-sm font-medium text-gray-700">
                    Categoría <span class="text-red-500">*</span>
                  </label>
                  <div class="flex space-x-2">
                    <button 
                      v-if="form.category"
                      type="button"
                      @click="editCurrentCategory"
                      class="text-xs text-amber-600 hover:text-amber-700 font-medium flex items-center"
                      title="Editar categoría seleccionada"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                      Editar
                    </button>
                    <button 
                      type="button" 
                      @click="openNewCategoryForm"
                      class="text-xs text-amber-600 hover:text-amber-700 font-medium flex items-center"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      Nueva
                    </button>
                  </div>
                </div>
                
                <!-- Formulario para gestionar categorías -->
                <div v-if="categoryForm.showForm" class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                  <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-medium text-gray-700">
                      {{ categoryForm.id ? 'Editar' : 'Nueva' }} Categoría
                    </h4>
                    <div class="flex space-x-2">
                      <button 
                        v-if="categoryForm.id"
                        type="button"
                        @click="deleteCategory"
                        class="p-1 text-red-500 hover:text-red-700 focus:outline-none"
                        title="Eliminar categoría"
                        :disabled="isLoading"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                      <button 
                        type="button"
                        @click="closeCategoryForm"
                        class="p-1 text-gray-500 hover:text-gray-700 focus:outline-none"
                        :title="categoryForm.id ? 'Cancelar edición' : 'Cerrar formulario'"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                      <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
                        <input 
                          v-model="categoryForm.name"
                          type="text" 
                          maxlength="30"
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                          placeholder="Ej: Bebidas Calientes"
                          @keyup.enter="saveCategory"
                        />
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Ícono</label>
                        <div class="flex flex-wrap gap-2 border border-gray-200 rounded-lg p-2 bg-white">
                          <div 
                            v-for="(emoji, index) in availableEmojis" 
                            :key="index"
                            @click="categoryForm.emoji = emoji"
                            class="w-8 h-8 flex items-center justify-center text-lg rounded-full cursor-pointer transition-all transform hover:scale-110"
                            :class="categoryForm.emoji === emoji ? 'bg-amber-100 border-2 border-amber-500 scale-110' : 'bg-white border border-gray-200 hover:bg-gray-50'"
                            :title="getEmojiNameByIndex(index)"
                          >
                            {{ emoji }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="flex flex-col justify-between">
                      <div>
                        <p class="text-xs text-gray-500 mb-2">Vista previa:</p>
                        <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg">
                          <span class="text-2xl mr-2">{{ categoryForm.emoji || '📦' }}</span>
                          <span class="font-medium">{{ categoryForm.name || 'Nombre de categoría' }}</span>
                        </div>
                      </div>
                      <button
                        type="button"
                        @click="saveCategory"
                        class="w-full mt-4 px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors"
                        :disabled="!categoryForm.name.trim()"
                        :class="{'opacity-50 cursor-not-allowed': !categoryForm.name.trim()}"
                      >
                        {{ categoryForm.id ? 'Actualizar Categoría' : 'Crear Categoría' }}
                      </button>
                    </div>
                  </div>
                </div>
                
                <div class="relative">
                  <select 
                    v-model="form.category"
                    required
                    class="block w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white cursor-pointer appearance-none"
                    :class="{'border-red-500': formError && !form.category}"
                    style="background-image: none;"
                  >
                    <option value="" disabled>Selecciona una categoría</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id.toString()">
                      {{ getCategoryEmoji(category.id) }} {{ category.name }}
                    </option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
                <p v-if="formError && !form.category" class="mt-1 text-xs text-red-500">Debes seleccionar una categoría</p>
              </div>
              
              <!-- Imagen -->
              <div class="space-y-1.5">
                <label class="block text-sm font-medium text-gray-700">
                  Imagen
                </label>
                <div class="mt-1 flex items-center">
                  <label class="cursor-pointer">
                    <span class="sr-only">Seleccionar imagen</span>
                    <input 
                      type="file"
                      accept="image/*"
                      @change="handleImageUpload"
                      class="hidden"
                      id="image-upload"
                    />
                    <div class="flex items-center px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                      <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <span class="text-sm text-gray-600">{{ form.image ? 'Cambiar imagen' : 'Seleccionar imagen' }}</span>
                    </div>
                  </label>
                </div>
                <p class="text-xs text-gray-500">Formatos: JPEG, PNG, JPG, GIF (Máx. 2MB)</p>
                
                <!-- Vista previa de la imagen -->
                <div v-if="imagePreview || form.image" class="mt-3">
                  <p class="text-xs font-medium text-gray-700 mb-1">Vista previa:</p>
                  <div class="relative inline-block">
                    <img 
                      :src="imagePreview || form.image" 
                      class="h-24 w-24 object-cover rounded-lg border-2 border-amber-100"
                    />
                    <button 
                      v-if="imagePreview || form.image"
                      @click="removeImage"
                      class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
                      type="button"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
                
              <!-- Pie del formulario -->
              <div class="pt-4 border-t border-gray-100">
                <div class="flex justify-end space-x-3">
                  <button 
                    type="button"
                    @click="closeModal"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors"
                  >
                    Cancelar
                  </button>
                  <button 
                    type="submit"
                    :disabled="!formDirty || saving || saved"
                    :class="[
                      'px-5 py-2.5 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors',
                      saving 
                        ? 'bg-gray-500 cursor-not-allowed' 
                        : saved 
                          ? 'bg-amber-800 hover:bg-amber-900' 
                          : 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500',
                      (!formDirty || saved) ? 'opacity-75' : ''
                    ]"
                  >
                    <span v-if="saving" class="flex items-center">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      {{ showEditModal ? 'Actualizando...' : 'Guardando...' }}
                    </span>
                    <span v-else-if="saved" class="flex items-center">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      {{ showEditModal ? '¡Actualizado!' : '¡Guardado!' }}
                    </span>
                    <span v-else>
                      {{ showEditModal ? 'Actualizar producto' : 'Crear producto' }}
                    </span>
                  </button>
                </div>
                
                <p v-if="formError" class="mt-3 text-sm text-red-600 text-right">
                  {{ formError }}
                </p>
                
                <div class="mt-4 pt-4 border-t border-gray-100">
                  <p class="text-xs text-gray-500 text-center">
                    Los campos marcados con <span class="text-red-500">*</span> son obligatorios
                  </p>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
  </AdminLayout>
</template>

<script setup>
import { ref, defineProps, watch, nextTick } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
// Loading state
const isLoading = ref(false)

// Función para mostrar diálogo de confirmación
const showConfirmDialog = (message, title = 'Confirmar') => {
  return new Promise((resolve) => {
    if (window.confirm(`${title}\n\n${message}`)) {
      resolve(true)
    } else {
      resolve(false)
    }
  })
}

// Props desde Inertia (AdminController@products) - CORREGIDO
const props = defineProps({
  products: { 
    type: Array, 
    default: () => [] 
  },
  categories: { 
    type: Array, 
    default: () => [] 
  }
})

// Reactive data
const products = ref(props.products)
const categories = ref(props.categories)
const loading = ref(false)
const error = ref('')
const showCreateModal = ref(false)
const showEditModal = ref(false)
const saving = ref(false)
const editingProduct = ref(null)
const imagePreview = ref(null)
const deletingId = ref(null)
const deletingSelected = ref(false)
const deletingAll = ref(false)
const formDirty = ref(false)
const saved = ref(false)
const selectedProducts = ref([])
const showUndo = ref(false)
const undoData = ref(null)
const undoTimer = ref(5)
const undoTimeout = ref(null)

// La función deleteCategory ya incluye la confirmación

// Estado para el formulario de categoría
const categoryForm = ref({
  id: null,
  name: '',
  emoji: '☕',
  showForm: false
})

// Emojis disponibles para las categorías (debe coincidir con el backend)
const availableEmojis = ref([
  '☕', '🍵', '🍰', '🥐', '🥗', '🧃', '🍫', '🍩', '🍪', '🥤', 
  '🍷', '🍺', '🥂', '🍹', '🍸', '🥃', '🍾', '🍶', '🍼', '🍻', 
  '🧋', '🧉', '🧊', '🍽️', '🍴', '🥄', '🔪', '🏺', '📦'
]);

// Mapeo de emojis a nombres descriptivos
const emojiNames = {
  '☕': 'Café',
  '🍵': 'Té',
  '🍰': 'Pastel',
  '🥐': 'Croissant',
  '🥗': 'Ensalada',
  '🧃': 'Jugo',
  '🍫': 'Chocolate',
  '🍩': 'Donut',
  '🍪': 'Galleta',
  '🥤': 'Bebida',
  '🍷': 'Vino',
  '🍺': 'Cerveza',
  '🥂': 'Brindis',
  '🍹': 'Cóctel',
  '🍸': 'Cóctel',
  '🥃': 'Whisky',
  '🍾': 'Champán',
  '🍶': 'Sake',
  '🍼': 'Biberón',
  '🍻': 'Cervezas',
  '🧋': 'Té de burbujas',
  '🧉': 'Mate',
  '🧊': 'Hielo',
  '🍽️': 'Cubiertos',
  '🍴': 'Cubiertos',
  '🥄': 'Cuchara',
  '🔪': 'Cuchillo',
  '🏺': 'Jarrón',
  '📦': 'Caja'
};

// Obtener el emoji por su índice
const getEmojiByIndex = (index) => {
  return availableEmojis.value[index] || '📦';
};

// Obtener el nombre descriptivo de un emoji por su índice
const getEmojiNameByIndex = (index) => {
  const emoji = getEmojiByIndex(index);
  return emojiNames[emoji] || 'Otro';
};

const form = ref({
  id: null,
  name: '',
  description: '',
  price: 1000,
  stock: 0,
  category: '',
  image: null,
  _method: 'post',
  image_url: null,
  remove_image: false
})

const formError = ref('')

// Watch para actualizar productos cuando cambian las props
watch(() => props.products, (val) => {
  products.value = val || []
  selectedProducts.value = []
  
  if (!showUndo.value && !undoData.value) {
    if (undoTimeout.value) {
      clearTimeout(undoTimeout.value)
      undoTimeout.value = null
    }
  }
})

// Validar precio
const validatePrice = () => {
  // Si el valor está vacío, no hacer nada
  if (form.value.price === '' || form.value.price === null) return
  
  // Convertir a número
  let price = parseFloat(form.value.price)
  
  // Si no es un número válido, establecer el valor mínimo
  if (isNaN(price)) {
    form.value.price = 1000
    return
  }
  
  // Asegurar que esté dentro del rango
  if (price < 1000) {
    form.value.price = 1000
  } else if (price > 100000) {
    form.value.price = 100000
  } else {
    // Redondear al múltiplo de 100 más cercano
    form.value.price = Math.round(price / 100) * 100
  }
  
  formDirty.value = true
}

// Validar stock mientras se escribe
const validateStock = () => {
  if (form.value.stock === '') return
  
  let stock = parseInt(form.value.stock)
  
  if (isNaN(stock)) {
    form.value.stock = ''
    return
  }
  
  // Asegurar que esté dentro del rango 0-1000
  if (stock < 0) {
    form.value.stock = 0
  } else if (stock > 1000) {
    form.value.stock = 1000
  } else {
    form.value.stock = stock
  }
  
  formDirty.value = true
}

// Manejar carga de imagen
const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (!file) return
  
  // Verificar tipo de archivo
  const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
  if (!validTypes.includes(file.type)) {
    alert('Formato de imagen no válido. Use JPEG, PNG, GIF o WebP.')
    return
  }
  
  // Verificar tamaño máximo de 10MB
  if (file.size > 10 * 1024 * 1024) {
    alert('La imagen no debe superar los 10MB')
    return
  }
  
  form.value.image = file
  imagePreview.value = URL.createObjectURL(file)
  formDirty.value = true
  event.target.value = ''
}

// Eliminar imagen seleccionada
const removeImage = () => {
  form.value.image = null
  form.value.image_url = null
  imagePreview.value = null
  formDirty.value = true
  
  // Si estás editando y había una imagen anterior, la marcamos para eliminarla
  if (form.value.id) {
    form.value.remove_image = true
  }
}

// Methods
// La función handleImageUpload ya está definida arriba, la eliminamos aquí para evitar duplicados

const editProduct = (product) => {
  editingProduct.value = product
  
  // Asegurarse de que el ID de la categoría sea un string
  const categoryId = product.category_id || product.category?.id || ''
  
  form.value = {
    id: product.id,
    name: product.name,
    description: product.description || '',
    price: product.price,
    stock: product.stock,
    category: categoryId ? categoryId.toString() : '',
    image: null,
    _method: 'put',
    image_url: product.image_url,
    remove_image: false
  }
  
  // Forzar actualización del select de categorías
  nextTick(() => {
    form.value.category = categoryId ? categoryId.toString() : ''
    
    // Si hay una categoría, asegurarse de que exista en la lista de categorías
    if (categoryId && !props.categories.some(c => c.id.toString() === categoryId.toString())) {
      console.warn(`La categoría con ID ${categoryId} no se encuentra en la lista de categorías disponibles`)
    }
  })
  
  imagePreview.value = product.image_url || null
  showEditModal.value = true
  formDirty.value = false
  saved.value = false
  window.scrollTo({ top: 0, behavior: 'smooth' })
  
  // Depuración
  console.log('Editando producto:', product)
  console.log('Categorías disponibles:', props.categories)
  console.log('Categoría seleccionada:', form.value.category)
}

// Crear nueva categoría
const createCategory = async () => {
  if (!categoryForm.name.trim()) return
  
  try {
    const response = await axios.post('/admin/categories', {
      name: categoryForm.name.trim(),
      emoji: categoryForm.emoji
    })
    
    // Actualizar la lista de categorías
    const newCategory = response.data
    props.categories.push(newCategory)
    
    // Seleccionar la nueva categoría automáticamente
    form.value.category = newCategory.id.toString()
    
    // Resetear el formulario
    categoryForm.name = ''
    categoryForm.emoji = '☕'
    categoryForm.showForm = false
    
    // Mostrar mensaje de éxito
    alert('Categoría creada exitosamente')
  } catch (error) {
    console.error('Error al crear categoría:', error)
    alert('Error al crear la categoría. Por favor, intente nuevamente.')
  }
}

const sleep = (ms) => new Promise(r => setTimeout(r, ms))

const saveProduct = async () => {
  formError.value = ''
  saving.value = true
  saved.value = false
  
  try {
    // Validaciones adicionales
    if (!form.value.name || form.value.name.trim() === '') {
      formError.value = 'El nombre del producto es obligatorio'
      saving.value = false
      return
    }
    
    if (form.value.name.length > 50) {
      formError.value = 'El nombre no puede tener más de 50 caracteres'
      saving.value = false
      return
    }
    
    if (!form.value.description || form.value.description.trim() === '') {
      formError.value = 'La descripción es obligatoria'
      saving.value = false
      return
    }
    
    if (form.value.description.length > 200) {
      formError.value = 'La descripción no puede tener más de 200 caracteres'
      saving.value = false
      return
    }
    
    if (!form.value.price || form.value.price < 1000 || form.value.price > 100000) {
      formError.value = 'El precio debe estar entre ₲ 1,000 y ₲ 100,000'
      saving.value = false
      return
    }
    
    if (form.value.stock === '' || form.value.stock < 0 || form.value.stock > 1000) {
      formError.value = 'El stock debe estar entre 0 y 1,000 unidades'
      saving.value = false
      return
    }
    
    if (!form.value.category) {
      formError.value = 'Debe seleccionar una categoría'
      saving.value = false
      return
    }
    
    const url = showEditModal.value 
      ? `/admin/products/${form.value.id}`
      : '/admin/products'
    
    const formData = new FormData()
    
    // Agregar todos los campos del formulario
    for (const key in form.value) {
      if (form.value[key] !== null && form.value[key] !== undefined) {
        // Manejar campos especiales
        if (key === 'price' || key === 'stock') {
          formData.append(key, Number(form.value[key]))
        } else if (key === 'image' && form.value[key] instanceof File) {
          formData.append('image', form.value[key])
        } else if (key === 'remove_image' && form.value[key] === true) {
          formData.append('remove_image', '1')
        } else if (key === 'category') {
          formData.append('category', form.value[key])
        } else if (key !== 'image_url' && key !== 'id') {
          formData.append(key, form.value[key])
        }
      }
    }
    
    // Usar el router de Inertia para el envío del formulario
    const method = showEditModal.value ? 'put' : 'post'
    
    // Si es una actualización, asegurarse de incluir el método PUT
    if (showEditModal.value) {
      formData.append('_method', 'PUT')
    }
    
    await router.post(url, formData, {
      onSuccess: () => {
        saved.value = true
        formDirty.value = false
        // Cerrar el modal después de 1.5 segundos
        setTimeout(() => {
          closeModal()
        }, 1500)
      },
      onError: (errors) => {
        if (errors.message) {
          formError.value = errors.message
        } else if (errors.errors) {
          // Mostrar el primer error de validación del servidor
          const firstError = Object.values(errors.errors)[0][0]
          formError.value = firstError
        } else {
          formError.value = showEditModal.value 
            ? 'Error al actualizar el producto. Por favor, verifica los datos.'
            : 'Error al crear el producto. Por favor, verifica los datos.'
        }
      },
      onFinish: () => {
        saving.value = false
      },
      forceFormData: true,
      preserveScroll: true
    })
    
  } catch (error) {
    console.error('Error al guardar el producto:', error)
    formError.value = 'Ocurrió un error inesperado al guardar el producto. Por favor, intente nuevamente.'
    saving.value = false
  }
}

const deleteProduct = async (productId) => {
  deletingId.value = productId

  try {
    const productToDelete = products.value.find(p => p.id === productId)
    undoData.value = {
      type: 'selected',
      products: [productToDelete],
      timestamp: Date.now()
    }

    await router.post(`/admin/products/${productId}`, {
      _method: 'DELETE'
    }, {
      preserveScroll: true,
      onSuccess: () => {
        startUndoTimer()
        router.reload({ only: ['products'] })
      },
      onError: () => {
        error.value = 'No se pudo eliminar el producto'
        undoData.value = null
      }
    })
  } finally {
    deletingId.value = null
  }
}

const deleteAllProducts = async () => {
  if (products.value.length === 0) return

  const confirmMessage = `¿Estás COMPLETAMENTE SEGURO de que quieres eliminar TODOS los productos (${products.value.length})? Esta acción no se puede deshacer fácilmente.`
  if (!confirm(confirmMessage)) return

  deletingAll.value = true

  try {
    undoData.value = {
      type: 'all',
      products: [...products.value],
      timestamp: Date.now()
    }

    await router.post('/admin/products/delete-all', {}, {
      preserveScroll: true,
      onSuccess: () => {
        startUndoTimer()
        selectedProducts.value = []
        router.reload({ only: ['products'] })
      },
      onError: (errors) => {
        error.value = 'No se pudieron eliminar todos los productos'
        undoData.value = null
        console.error('Error details:', errors)
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
    await router.post('/admin/products/restore', {
      undo_data: undoData.value
    }, {
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ['products'] })
        undoData.value = null
      },
      onError: () => {
        error.value = 'No se pudieron restaurar los productos'
      }
    })
  } catch (error) {
    console.error('Error al restaurar productos:', error)
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

const deleteSelectedProducts = async () => {
  if (selectedProducts.value.length === 0) return

  const confirmMessage = `¿Estás seguro de que quieres eliminar ${selectedProducts.value.length} producto${selectedProducts.value.length !== 1 ? 's' : ''}?`
  if (!confirm(confirmMessage)) return

  deletingSelected.value = true

  try {
    const productsToDelete = products.value.filter(p => selectedProducts.value.includes(p.id))
    undoData.value = {
      type: 'selected',
      products: productsToDelete,
      timestamp: Date.now()
    }

    await router.post('/admin/products/bulk-delete', {
      product_ids: selectedProducts.value 
    }, {
      preserveScroll: true,
      onSuccess: () => {
        startUndoTimer()
        selectedProducts.value = []
        router.reload({ only: ['products'] })
      },
      onError: (errors) => {
        error.value = 'No se pudieron eliminar algunos productos'
        undoData.value = null
        console.error('Error details:', errors)
      }
    })
  } finally {
    deletingSelected.value = false
  }
}

// Obtener el emoji basado en el índice de la categoría
const getCategoryEmoji = (categoryId) => {
  const category = categories.value.find(cat => cat.id === categoryId);
  if (!category || category.emoji === null || category.emoji === undefined) return '📦';
  
  // Obtener el emoji usando el índice almacenado
  return getEmojiByIndex(category.emoji);
}

// Abrir formulario para nueva categoría
const openNewCategoryForm = () => {
  categoryForm.value = {
    id: null,
    name: '',
    emoji: availableEmojis.value[0], // Usar el primer emoji por defecto
    showForm: true
  }
}

// Editar la categoría actualmente seleccionada
const editCurrentCategory = () => {
  if (!form.value.category) {
    showNotification('Error', 'Selecciona una categoría para editar', 'error');
    return;
  }
  
  const category = props.categories.find(c => c.id.toString() === form.value.category);
  if (category) {
    categoryForm.value = {
      id: category.id,
      name: category.name,
      emoji: getEmojiByIndex(category.emoji) || availableEmojis.value[0],
      showForm: true
    };
  }
}

// Cerrar el formulario de categoría
const closeCategoryForm = () => {
  categoryForm.value.showForm = false;
  // Pequeño retraso para la animación
  setTimeout(() => {
    categoryForm.value = {
      id: null,
      name: '',
      emoji: availableEmojis.value[0],
      showForm: false
    };
  }, 200);
};

// Guardar o actualizar categoría
const saveCategory = async () => {
  try {
    if (!categoryForm.value.name || !categoryForm.value.name.trim()) {
      showNotification('Error', 'El nombre de la categoría es requerido', 'error')
      return
    }

    const isEditing = !!categoryForm.value.id
    const url = isEditing 
      ? `/admin/categories/${categoryForm.value.id}`
      : '/admin/categories'
    
    const method = isEditing ? 'put' : 'post'
    
    console.log('=== Intento de guardar categoría ===')
    console.log('URL:', url)
    console.log('Método:', method)
    console.log('Datos a enviar:', {
      name: categoryForm.value.name.trim()
      // El emoji ya no se envía al servidor, se maneja como atributo virtual
    })
    
    // Mostrar estado de carga
    isLoading.value = true

    try {
      console.log('Enviando solicitud a:', url)
      console.log('Método:', method)
      
      // Obtener el índice del emoji seleccionado
      const emojiIndex = availableEmojis.value.indexOf(categoryForm.value.emoji);
      if (emojiIndex === -1) {
        showNotification('Error', 'Selecciona un emoji válido', 'error');
        return;
      }

      // Preparar los datos a enviar al servidor
      const requestData = {
        name: categoryForm.value.name.trim(),
        emoji: emojiIndex // Enviar el índice numérico del emoji
      }
      
      console.log('Datos a enviar:', requestData)
      
      // Obtener el token CSRF
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || ''
      console.log('CSRF Token:', csrfToken ? 'Encontrado' : 'No encontrado')
      
      // Configuración de axios para incluir credenciales
      const config = {
        method,
        url,
        data: requestData,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken
        },
        withCredentials: true,
        // Asegurarse de que las credenciales se envíen con la solicitud
        xsrfCookieName: 'XSRF-TOKEN',
        xsrfHeaderName: 'X-XSRF-TOKEN',
        // Usar la URL completa para evitar problemas de CORS
        baseURL: window.location.origin
      };

      console.log('Configuración de la petición:', config);
      
      const response = await axios(config)
      
      console.log('Respuesta del servidor:', response)
      
      if (!response.data || !response.data.success) {
        throw new Error(response.data?.message || 'Respuesta inesperada del servidor')
      }
      
      const updatedCategory = response.data.data || response.data
      
      if (!updatedCategory) {
        throw new Error('No se recibieron datos de la categoría')
      }
      
      console.log('Categoría procesada:', updatedCategory)
      
      // Actualizar la lista de categorías
      if (isEditing) {
        const index = props.categories.findIndex(c => c.id === updatedCategory.id)
        if (index !== -1) {
          // Actualizar la categoría existente
          props.categories.splice(index, 1, updatedCategory)
        }
      } else {
        // Agregar la nueva categoría al principio del array
        props.categories.unshift(updatedCategory)
        // Seleccionar la nueva categoría automáticamente
        if (updatedCategory.id) {
          form.value.category = updatedCategory.id.toString()
        }
      }
      
      // Mostrar notificación de éxito
      showNotification(
        isEditing ? '✅ Categoría actualizada' : '✅ Categoría creada',
        response.data.message || `La categoría "${updatedCategory.name}" ha sido ${isEditing ? 'actualizada' : 'creada'} exitosamente`,
        'success'
      )
      
      // Cerrar el formulario después de guardar
      closeCategoryForm()
      
      return updatedCategory
    } catch (error) {
      console.error('Error en la petición:', error)
      
      let errorMessage = 'Ocurrió un error al procesar la solicitud'
      
      if (error.response) {
        // El servidor respondió con un código de estado fuera del rango 2xx
        console.error('Datos de respuesta del error:', error.response.data)
        console.error('Estado de la respuesta:', error.response.status)
        
        if (error.response.status === 422) {
          // Error de validación
          const errors = error.response.data.errors || {}
          errorMessage = Object.values(errors).flat().join(' ')
        } else if (error.response.status === 404) {
          errorMessage = 'Recurso no encontrado'
        } else if (error.response.status === 403) {
          errorMessage = 'No tienes permiso para realizar esta acción'
        } else if (error.response.status === 500) {
          errorMessage = 'Error interno del servidor. Por favor, inténtalo de nuevo más tarde.'
        }
        
        errorMessage = error.response.data.message || errorMessage
      } else if (error.request) {
        // La solicitud fue hecha pero no se recibió respuesta
        console.error('No se recibió respuesta del servidor')
        errorMessage = 'No se pudo conectar con el servidor. Por favor, verifica tu conexión a internet.'
      } else {
        // Algo más causó el error
        console.error('Error al configurar la solicitud:', error.message)
      }
      
      showNotification('❌ Error', errorMessage, 'error')
      throw error
    } finally {
      // Ocultar estado de carga
      isLoading.value = false
    }
  } catch (error) {
    console.error('Error inesperado en saveCategory:', error)
    showNotification(
      '❌ Error inesperado',
      'Ha ocurrido un error inesperado. Por favor, revisa la consola para más detalles.',
      'error'
    )
  }
}

// Eliminar categoría
const deleteCategory = async () => {
  try {
    if (!categoryForm.value.id) {
      console.error('No se proporcionó un ID de categoría para eliminar')
      showNotification('❌ Error', 'No se proporcionó un ID de categoría válido', 'error')
      return
    }

    // Confirmar antes de eliminar
    const shouldDelete = await showConfirmDialog(
      '¿Estás seguro de que deseas eliminar esta categoría?',
      'Confirmar eliminación'
    )
    
    if (!shouldDelete) {
      console.log('Eliminación cancelada por el usuario')
      return
    }
    
    console.log('=== Intento de eliminar categoría ===')
    console.log('ID de categoría a eliminar:', categoryForm.value.id)
    
    // Mostrar estado de carga
    isLoading.value = true
    
    try {
      // Obtener el token CSRF
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || ''
      
      // Hacer la petición de eliminación a la ruta web
      const response = await axios({
        method: 'delete',
        url: `/admin/categories/${categoryForm.value.id}`,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken
        },
        withCredentials: true
      })
      
      console.log('Respuesta de eliminación:', response.data)
      
      if (response.data && response.data.success) {
        console.log('Categoría eliminada exitosamente')
        
        // Obtener el nombre de la categoría eliminada para el mensaje
        const deletedCategoryName = categoryForm.value.name || 'la categoría'
        
        // Actualizar la lista de categorías
        const index = props.categories.findIndex(c => c.id === categoryForm.value.id)
        if (index !== -1) {
          props.categories.splice(index, 1)
        }
        
        // Si la categoría eliminada estaba seleccionada, limpiar la selección
        if (form.value.category === categoryForm.value.id.toString()) {
          form.value.category = ''
        }
        
        // Mostrar notificación de éxito
        showNotification(
          '✅ Categoría eliminada',
          `La categoría "${deletedCategoryName}" ha sido eliminada exitosamente`,
          'success'
        )
        
        // Cerrar el formulario
        closeCategoryForm()
      } else {
        throw new Error(response.data?.message || 'Error desconocido al eliminar la categoría')
      }
    } catch (error) {
      console.error('Error al eliminar categoría:', error)
      let errorMessage = 'Ocurrió un error al intentar eliminar la categoría'
      
      if (error.response) {
        // El servidor respondió con un error
        console.error('Datos de respuesta del error:', error.response.data)
        console.error('Estado de la respuesta:', error.response.status)
        
        if (error.response.status === 422) {
          // Error de validación
          errorMessage = error.response.data.message || 'No se puede eliminar la categoría porque tiene productos asociados'
          
          // Si hay productos asociados
          if (error.response.data.product_count > 0) {
            errorMessage = `No se puede eliminar la categoría porque tiene ${error.response.data.product_count} productos asociados.`
          }
        } else if (error.response.status === 404) {
          errorMessage = 'La categoría no fue encontrada o ya ha sido eliminada'
        } else if (error.response.status === 403) {
          errorMessage = 'No tienes permisos para eliminar esta categoría'
        } else if (error.response.status === 500) {
          errorMessage = 'Error interno del servidor. Por favor, inténtalo de nuevo más tarde.'
        }
        
        errorMessage = error.response.data?.message || errorMessage
      } else if (error.request) {
        // La solicitud fue hecha pero no se recibió respuesta
        console.error('No se recibió respuesta del servidor')
        errorMessage = 'No se pudo conectar con el servidor. Por favor, verifica tu conexión a internet.'
      } else if (error.message) {
        // Algo más causó el error
        console.error('Error al configurar la solicitud:', error.message)
        errorMessage = error.message || errorMessage
      }
      
      showNotification('❌ Error al eliminar', errorMessage, 'error')
    }
  } catch (error) {
    console.error('Error inesperado al procesar la eliminación:', error)
    showNotification(
      '❌ Error inesperado',
      'Ha ocurrido un error inesperado al intentar eliminar la categoría. Por favor, revisa la consola para más detalles.',
      'error'
    )
  } finally {
    // Ocultar estado de carga
    isLoading.value = false
  }
}

// Mostrar notificación
const showNotification = (title, message, type = 'success') => {
  const notification = document.createElement('div')
  notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform ${
    type === 'success' ? 'bg-green-500' : 'bg-red-500'
  } text-white`
  
  notification.innerHTML = `
    <div class="flex items-start">
      <div class="flex-shrink-0">
        ${type === 'success' ? '✅' : '❌'}
      </div>
      <div class="ml-3">
        <h3 class="text-sm font-medium">${title}</h3>
        <p class="text-sm opacity-90">${message}</p>
      </div>
    </div>
  `
  
  document.body.appendChild(notification)
  
  // Eliminar la notificación después de 5 segundos
  setTimeout(() => {
    notification.classList.add('opacity-0', 'translate-x-8')
    setTimeout(() => {
      document.body.removeChild(notification)
    }, 300)
  }, 5000)
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  editingProduct.value = null
  form.value = {
    id: null,
    name: '',
    description: '',
    price: 1000,
    stock: 0,
    category: '',
    image: null,
    _method: 'post',
    image_url: null,
    remove_image: false
  }
  // Resetear el formulario de categoría
  closeCategoryForm()
  imagePreview.value = null
  formError.value = ''
  saved.value = false
  formDirty.value = false
}

// Marcar formulario sucio cuando se cambien campos
watch(() => [form.value.name, form.value.description, form.value.price, form.value.stock, form.value.category], () => {
  formDirty.value = true
})

const handleImageError = (event, product) => {
  event.target.onerror = null
  event.target.src = '/images/placeholder.jpg'
  if (product) product.image_url = '/images/placeholder.jpg'
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
.line-clamp-2 {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>