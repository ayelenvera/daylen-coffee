<!-- resources/js/Pages/Admin/CustomizationRules.vue -->
<template>
    <AdminLayout>
      <Head title="Reglas de Personalización" />
      
      <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Header -->
          <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reglas de Personalización</h1>
            <p class="text-gray-600 mt-2">
              Configura qué opciones de personalización mostrar para cada categoría de productos
            </p>
          </div>
  
          <!-- Reglas existentes -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div 
              v-for="rule in rules" 
              :key="rule.id"
              class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
            >
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">
                    {{ rule.category_name }}
                  </h3>
                  <p class="text-gray-600 text-sm">
                    {{ rule.enabled_options.length }} opciones habilitadas
                  </p>
                </div>
                <button 
                  @click="deleteRule(rule.id)"
                  class="text-red-600 hover:text-red-800 p-1"
                  title="Eliminar regla"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
  
              <!-- Opciones habilitadas -->
              <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Opciones habilitadas:</h4>
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-for="option in rule.enabled_options" 
                    :key="option"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                  >
                    {{ availableOptions[option] }}
                  </span>
                </div>
              </div>
  
              <!-- Opciones de tamaño -->
              <div v-if="rule.size_options && rule.size_options.length > 0" class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Tamaños disponibles:</h4>
                <div class="flex flex-wrap gap-1">
                  <span 
                    v-for="size in rule.size_options" 
                    :key="size.name"
                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800"
                  >
                    {{ size.name }} (+₲{{ size.price?.toLocaleString() || 0 }})
                  </span>
                </div>
                <p v-if="rule.default_size" class="text-xs text-gray-600 mt-1">
                  Tamaño por defecto: <strong>{{ rule.default_size }}</strong>
                </p>
              </div>
  
              <!-- Opciones de azúcar -->
              <div v-if="rule.sugar_options && rule.sugar_options.length > 0" class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Niveles de azúcar:</h4>
                <div class="flex flex-wrap gap-1">
                  <span 
                    v-for="sugar in rule.sugar_options" 
                    :key="sugar"
                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-amber-100 text-amber-800"
                  >
                    {{ sugar }}
                  </span>
                </div>
              </div>
  
              <!-- Coberturas -->
              <div v-if="rule.topping_options && rule.topping_options.length > 0" class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Coberturas:</h4>
                <div class="flex flex-wrap gap-1">
                  <span 
                    v-for="topping in rule.topping_options" 
                    :key="topping.name"
                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800"
                  >
                    {{ topping.name }} (+₲{{ topping.price?.toLocaleString() || 0 }})
                  </span>
                </div>
              </div>
  
              <!-- Agregados -->
              <div v-if="rule.addon_options && rule.addon_options.length > 0" class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Agregados:</h4>
                <div class="flex flex-wrap gap-1">
                  <span 
                    v-for="addon in rule.addon_options" 
                    :key="addon.name"
                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800"
                  >
                    {{ addon.name }} (+₲{{ addon.price?.toLocaleString() || 0 }})
                  </span>
                </div>
              </div>
  
              <!-- Productos excluidos -->
              <div v-if="rule.excluded_products && rule.excluded_products.length > 0">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Productos excluidos:</h4>
                <p class="text-xs text-gray-600">
                  {{ rule.excluded_products.length }} producto(s) excluido(s) de esta regla
                </p>
              </div>
            </div>
          </div>
  
          <!-- Formulario para crear/editar regla -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">
              {{ editingRule ? 'Editar Regla' : 'Crear Nueva Regla' }}
            </h2>
  
            <form @submit.prevent="saveRule" class="space-y-6">
              <!-- Selección de categoría -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Categoría *
                </label>
                <select 
                  v-model="form.category_id"
                  required
                  class="w-full border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                  :disabled="editingRule"
                >
                  <option value="">Selecciona una categoría</option>
                  <option 
                    v-for="category in availableCategories" 
                    :key="category.id"
                    :value="category.id"
                    :disabled="hasRuleForCategory(category.id)"
                  >
                    {{ getCategoryEmoji(category.emoji) }} {{ category.name }}
                  </option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                  Solo puedes tener una regla por categoría
                </p>
              </div>
  
              <!-- Opciones habilitadas -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Opciones de Personalización *
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                  <label 
                    v-for="(label, key) in availableOptions" 
                    :key="key"
                    class="flex items-center space-x-2 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer"
                    :class="{ 'bg-amber-50 border-amber-300': form.enabled_options.includes(key) }"
                  >
                    <input 
                      type="checkbox" 
                      :value="key"
                      v-model="form.enabled_options"
                      class="rounded border-gray-300 text-amber-600 focus:ring-amber-500"
                    >
                    <span class="text-sm text-gray-700">{{ label }}</span>
                  </label>
                </div>
              </div>
  
              <!-- TAMAÑOS -->
              <div v-if="form.enabled_options.includes('size')">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Opciones de Tamaño con Precios
                </label>
                <div class="space-y-3">
                  <div 
                    v-for="(sizeOption, index) in form.size_options" 
                    :key="index"
                    class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg"
                  >
                    <div class="flex-1 grid grid-cols-2 gap-2">
                      <div>
                        <label class="block text-xs text-gray-600 mb-1">Nombre del tamaño</label>
                        <input 
                          v-model="sizeOption.name"
                          type="text"
                          class="w-full border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                          placeholder="Ej: Pequeño"
                        />
                      </div>
                      <div>
                        <label class="block text-xs text-gray-600 mb-1">Precio adicional</label>
                        <div class="flex items-center">
                          <span class="text-gray-500 mr-1">₲</span>
                          <input 
                            v-model.number="sizeOption.price"
                            type="number"
                            class="w-full border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                            placeholder="0"
                            min="0"
                            step="1000"
                          />
                        </div>
                      </div>
                    </div>
                    <button 
                      type="button"
                      @click="removeSizeOption(index)"
                      class="text-red-600 hover:text-red-800 p-1"
                      v-if="form.size_options.length > 1"
                      title="Eliminar tamaño"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </div>
                  <button 
                    type="button"
                    @click="addSizeOption"
                    class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Agregar tamaño</span>
                  </button>
                  
                  <!-- Tamaño por defecto -->
                  <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-200">
                    <label class="block text-sm font-medium text-amber-700 mb-2">
                      Tamaño por Defecto
                    </label>
                    <select 
                      v-model="form.default_size"
                      class="w-full border-amber-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 bg-white"
                    >
                      <option value="">Selecciona el tamaño por defecto</option>
                      <option 
                        v-for="(sizeOption, index) in form.size_options" 
                        :key="index"
                        :value="sizeOption.name"
                      >
                        {{ sizeOption.name }} (+₲{{ sizeOption.price?.toLocaleString() || 0 }})
                      </option>
                    </select>
                    <p class="text-xs text-amber-600 mt-1">
                      Este será el tamaño seleccionado automáticamente en el carrito
                    </p>
                  </div>
                </div>
              </div>
  
              <!-- AZÚCAR -->
              <div v-if="form.enabled_options.includes('sugar')">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Niveles de Azúcar
                </label>
                <div class="space-y-2">
                  <div 
                    v-for="(sugar, index) in form.sugar_options" 
                    :key="index"
                    class="flex items-center space-x-2"
                  >
                    <input 
                      v-model="form.sugar_options[index]"
                      type="text"
                      class="flex-1 border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                      placeholder="Ej: Sin azúcar"
                    >
                    <button 
                      type="button"
                      @click="removeSugarOption(index)"
                      class="text-red-600 hover:text-red-800 p-1"
                      v-if="form.sugar_options.length > 1"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </div>
                  <button 
                    type="button"
                    @click="addSugarOption"
                    class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Agregar nivel</span>
                  </button>
                </div>
              </div>
  
              <!-- COBERTURAS -->
              <div v-if="form.enabled_options.includes('toppings')">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Opciones de Coberturas
                </label>
                <div class="space-y-3">
                  <div 
                    v-for="(topping, index) in form.topping_options" 
                    :key="index"
                    class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg"
                  >
                    <div class="flex-1 grid grid-cols-2 gap-2">
                      <div>
                        <label class="block text-xs text-gray-600 mb-1">Nombre</label>
                        <input 
                          v-model="topping.name"
                          type="text"
                          class="w-full border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                          placeholder="Ej: Chocolate"
                        />
                      </div>
                      <div>
                        <label class="block text-xs text-gray-600 mb-1">Precio adicional</label>
                        <div class="flex items-center">
                          <span class="text-gray-500 mr-1">₲</span>
                          <input 
                            v-model.number="topping.price"
                            type="number"
                            class="w-full border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                            placeholder="2000"
                            min="0"
                            step="1000"
                          />
                        </div>
                      </div>
                    </div>
                    <button 
                      type="button"
                      @click="removeToppingOption(index)"
                      class="text-red-600 hover:text-red-800 p-1"
                      title="Eliminar cobertura"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </div>
                  <button 
                    type="button"
                    @click="addToppingOption"
                    class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Agregar cobertura</span>
                  </button>
                </div>
              </div>
  
              <!-- AGREGADOS -->
              <div v-if="form.enabled_options.includes('addons')">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Opciones de Agregados
                </label>
                <div class="space-y-3">
                  <div 
                    v-for="(addon, index) in form.addon_options" 
                    :key="index"
                    class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg"
                  >
                    <div class="flex-1 grid grid-cols-2 gap-2">
                      <div>
                        <label class="block text-xs text-gray-600 mb-1">Nombre</label>
                        <input 
                          v-model="addon.name"
                          type="text"
                          class="w-full border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                          placeholder="Ej: Leche extra"
                        />
                      </div>
                      <div>
                        <label class="block text-xs text-gray-600 mb-1">Precio adicional</label>
                        <div class="flex items-center">
                          <span class="text-gray-500 mr-1">₲</span>
                          <input 
                            v-model.number="addon.price"
                            type="number"
                            class="w-full border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"
                            placeholder="1000"
                            min="0"
                            step="1000"
                          />
                        </div>
                      </div>
                    </div>
                    <button 
                      type="button"
                      @click="removeAddonOption(index)"
                      class="text-red-600 hover:text-red-800 p-1"
                      title="Eliminar agregado"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </div>
                  <button 
                    type="button"
                    @click="addAddonOption"
                    class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center space-x-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Agregar opción</span>
                  </button>
                </div>
              </div>
  
              <!-- Productos excluidos -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Productos Excluidos (Opcional)
                </label>
                <p class="text-xs text-gray-500 mb-3">
                  Selecciona productos que NO deben mostrar ciertas opciones (ej: agua sin azúcar)
                </p>
                
                <select 
                  v-model="selectedProductToExclude"
                  class="w-full border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 mb-2"
                  @change="addExcludedProduct"
                >
                  <option value="">Selecciona un producto para excluir</option>
                  <option 
                    v-for="product in availableProducts" 
                    :key="product.id"
                    :value="product.id"
                    :disabled="form.excluded_products.includes(product.id)"
                  >
                    {{ product.name }} ({{ product.category_name }})
                  </option>
                </select>
  
                <div v-if="form.excluded_products.length > 0" class="space-y-2">
                  <div 
                    v-for="productId in form.excluded_products" 
                    :key="productId"
                    class="flex items-center justify-between p-2 bg-gray-50 rounded-lg"
                  >
                    <span class="text-sm text-gray-700">
                      {{ getProductName(productId) }}
                    </span>
                    <button 
                      type="button"
                      @click="removeExcludedProduct(productId)"
                      class="text-red-600 hover:text-red-800 p-1"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
  
              <!-- Botones -->
              <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button 
                  type="button"
                  @click="resetForm"
                  class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                >
                  Cancelar
                </button>
                <button 
                  type="submit"
                  :disabled="saving || !form.category_id || form.enabled_options.length === 0"
                  class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                  <span v-if="saving">Guardando...</span>
                  <span v-else>{{ editingRule ? 'Actualizar' : 'Crear' }} Regla</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup>
  import { ref, reactive, computed, onMounted } from 'vue'
  import { Head, router } from '@inertiajs/vue3'
  import AdminLayout from '@/Layouts/AdminLayout.vue'
  
  const props = defineProps({
    rules: Array,
    categories: Array,
    products: Array,
    available_options: Object,
    default_size_options: Array,
    default_sugar_options: Array,
    flash: Object
  })
  
  // Estado
  const saving = ref(false)
  const editingRule = ref(null)
  const selectedProductToExclude = ref('')
  
  // Formulario
  const form = reactive({
    category_id: '',
    enabled_options: ['quantity'],
    size_options: [
      { name: 'Pequeño', price: 0 },
      { name: 'Mediano', price: 2000 },
      { name: 'Grande', price: 4000 }
    ],
    default_size: 'Mediano',
    sugar_options: ['Sin azúcar', 'Poca', 'Normal', 'Extra'],
    topping_options: [
      { name: 'Chocolate', price: 2000 },
      { name: 'Vainilla', price: 2000 },
      { name: 'Caramelo', price: 3000 }
    ],
    addon_options: [
      { name: 'Leche extra', price: 1000 },
      { name: 'Crema batida', price: 2000 },
      { name: 'Canela', price: 500 }
    ],
    excluded_products: []
  })
  
  // Computed
  const availableCategories = computed(() => {
    return props.categories.filter(category => !hasRuleForCategory(category.id) || category.id === editingRule.value?.category_id)
  })
  
  const availableProducts = computed(() => {
    if (!form.category_id) return props.products
    
    return props.products.filter(product => {
      const productCategoryId = product.category?.toString()
      return productCategoryId === form.category_id.toString()
    })
  })
  
  const availableOptions = computed(() => props.available_options || {})
  
  // Métodos
  const hasRuleForCategory = (categoryId) => {
    return props.rules.some(rule => rule.category_id === categoryId && rule.id !== editingRule.value?.id)
  }
  
  const getCategoryEmoji = (emojiCode) => {
    const emojis = [
      '☕', '🍵', '🍰', '🥐', '🥗', '🧃', '🍫', '🍩', '🍪', '🥤', 
      '🍷', '🍺', '🥂', '🍹', '🍸', '🥃', '🍾', '🍶', '🍼', '🍻', 
      '🧋', '🧉', '🧊', '🍽️', '🍴', '🥄', '🔪', '🏺', '📦'
    ]
    return emojis[emojiCode] || '📦'
  }
  
  const getProductName = (productId) => {
    const product = props.products.find(p => p.id === productId)
    return product ? `${product.name} (${product.category_name || 'Sin categoría'})` : 'Producto no encontrado'
  }
  
  // Métodos para tamaños
  const addSizeOption = () => {
    form.size_options.push({ name: '', price: 0 })
  }
  
  const removeSizeOption = (index) => {
    form.size_options.splice(index, 1)
    if (form.default_size === form.size_options[index]?.name) {
      form.default_size = ''
    }
  }
  
  // Métodos para azúcar
  const addSugarOption = () => {
    form.sugar_options.push('')
  }
  
  const removeSugarOption = (index) => {
    form.sugar_options.splice(index, 1)
  }
  
  // Métodos para coberturas
  const addToppingOption = () => {
    form.topping_options.push({ name: '', price: 2000 })
  }
  
  const removeToppingOption = (index) => {
    form.topping_options.splice(index, 1)
  }
  
  // Métodos para agregados
  const addAddonOption = () => {
    form.addon_options.push({ name: '', price: 1000 })
  }
  
  const removeAddonOption = (index) => {
    form.addon_options.splice(index, 1)
  }
  
  // Métodos para productos excluidos
  const addExcludedProduct = () => {
    if (selectedProductToExclude.value && !form.excluded_products.includes(selectedProductToExclude.value)) {
      form.excluded_products.push(selectedProductToExclude.value)
      selectedProductToExclude.value = ''
    }
  }
  
  const removeExcludedProduct = (productId) => {
    form.excluded_products = form.excluded_products.filter(id => id !== productId)
  }
  
  const saveRule = async () => {
    saving.value = true
  
    try {
      const url = editingRule.value 
        ? `/admin/customization-rules/${editingRule.value.id}`
        : '/admin/customization-rules'
  
      const method = editingRule.value ? 'put' : 'post'
  
      // Usar visit en lugar de router[method] para manejar redirecciones de Inertia
      await router.visit(url, {
        method: method.toUpperCase(),
        data: form,
        preserveScroll: true,
        onSuccess: () => {
          resetForm()
          // No necesitamos recargar manualmente, Inertia maneja la redirección
        },
        onError: (errors) => {
          console.error('Error al guardar regla:', errors)
          // Mostrar errores de validación si es necesario
        },
        onFinish: () => {
          saving.value = false
        }
      })
    } catch (error) {
      console.error('Error:', error)
      saving.value = false
    }
  }
  
  const deleteRule = async (ruleId) => {
    if (!confirm('¿Estás seguro de que quieres eliminar esta regla?')) return
  
    try {
      await router.delete(`/admin/customization-rules/${ruleId}`, {
        preserveScroll: true,
        onSuccess: () => {
          // Inertia maneja la redirección automáticamente
        }
      })
    } catch (error) {
      console.error('Error al eliminar regla:', error)
    }
  }
  
  const resetForm = () => {
    form.category_id = ''
    form.enabled_options = ['quantity']
    form.size_options = [
      { name: 'Pequeño', price: 0 },
      { name: 'Mediano', price: 2000 },
      { name: 'Grande', price: 4000 }
    ]
    form.default_size = 'Mediano'
    form.sugar_options = ['Sin azúcar', 'Poca', 'Normal', 'Extra']
    form.topping_options = [
      { name: 'Chocolate', price: 2000 },
      { name: 'Vainilla', price: 2000 },
      { name: 'Caramelo', price: 3000 }
    ]
    form.addon_options = [
      { name: 'Leche extra', price: 1000 },
      { name: 'Crema batida', price: 2000 },
      { name: 'Canela', price: 500 }
    ]
    form.excluded_products = []
    editingRule.value = null
    selectedProductToExclude.value = ''
  }
  
  // Mostrar mensajes flash
  onMounted(() => {
    if (props.flash?.success) {
      alert(props.flash.success)
    }
    if (props.flash?.error) {
      alert(props.flash.error)
    }
  })
  </script>