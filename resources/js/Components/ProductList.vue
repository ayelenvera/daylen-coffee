<template>
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
      <!-- FILTROS AVANZADOS SIMPLIFICADOS -->
      <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <h2 class="text-2xl font-bold text-gray-900">Nuestros Productos</h2>
          
          <!-- Filtros combinados en una línea - COMENTADOS TEMPORALMENTE -->
          
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
              <input
                v-model="searchQuery"
                @input="handleSearchInput"
                type="text"
                placeholder="Buscar producto..."
                class="w-full sm:w-48 border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 pl-10 py-2"
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>

            <select 
              v-model="sortBy"
              @change="updateFilters"
              class="border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 py-2"
            >
              <option value="name">Orden: A-Z</option>
              <option value="name_desc">Orden: Z-A</option>
              <option value="price">Precio: ↑</option>
              <option value="price_desc">Precio: ↓</option>
              <option value="stock">Stock: ↑</option>
            </select>
          </div>
          
        </div>

        <!-- Filtros de categoría - MEJORADO -->
        <div class="mt-6">
          <div class="flex flex-wrap gap-2">
            <button 
              @click="handleCategoryClick(null)"
              :class="['px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 flex items-center border', 
                      !currentCategory ? 'bg-amber-600 text-white border-amber-700 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50']"
            >
              <span class="text-base mr-2">☕</span>
              <span class="font-medium">Todos los productos</span>
            </button>
            
            <button 
              v-for="cat in categories" 
              :key="cat.id"
              @click="handleCategoryClick(cat.id)"
              :class="['px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 flex items-center border', 
                      currentCategory === cat.id ? 'bg-amber-600 text-white border-amber-700 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50']"
              :title="cat.name"
            >
              <span class="text-base mr-2">{{ cat.emoji || '📦' }}</span>
              <span class="font-medium">{{ cat.name }}</span>
            </button>
          </div>
        </div>

        <!-- Información del filtro activo - COMENTADO TEMPORALMENTE -->
        
        <div v-if="(currentCategoryName || searchQuery || sortBy !== 'name') && products.length > 0" class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
          <p class="text-amber-800 text-sm">
            Filtros activos: 
            <span v-if="currentCategoryName" class="font-semibold">Categoría: {{ currentCategoryName }}</span>
            <span v-if="searchQuery" class="font-semibold ml-2">Búsqueda: "{{ searchQuery }}"</span>
            <span v-if="sortBy !== 'name'" class="font-semibold ml-2">Orden: {{ getSortText(sortBy) }}</span>
            <a href="/home" class="ml-2 text-amber-600 hover:text-amber-800 text-xs">(Limpiar filtros)</a>
          </p>
        </div>
        
      </div>
      <!-- 🎯 SECCIÓN DE PROMOCIONES - ENTRE FILTROS Y PRODUCTOS -->
      <div v-if="showPromotionalSection" class="mb-8">
        <PromotionalProducts :products="promotionalProducts" />
      </div>
      <!-- Products grid - MANTENER TAMAÑO ANTERIOR (3 columnas) -->
      <div v-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="product in filteredProducts" 
          :key="product.id"
          class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
        >
          <!-- Product image -->
          <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-t-lg overflow-hidden">
            <img 
              :src="product.image_url || '/images/placeholder.jpg'" 
              :alt="product.name"
              class="w-full h-48 object-cover rounded-t-lg"
              @error="handleImageError($event, product)"
            />
          </div>

          <!-- Product info -->
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
            <p class="text-md text-gray-600 mb-3 line-clamp-2">{{ product.description }}</p>
            
            <!-- Categoría del producto -->
            <div v-if="product.category_relation" class="mb-3">
              <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100">
                <span class="text-base mr-2">{{ product.category_relation.emoji || '📦' }}</span>
                <span class="font-medium">{{ product.category_relation.name || 'Sin categoría' }}</span>
              </span>
            </div>
            
            <div class="flex items-center justify-between mb-4">
              <span class="text-2xl font-bold text-amber-600">₲ {{ Number(product.price || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</span>
              <span class="text-sm text-gray-500">Stock: {{ product.stock }}</span>
            </div>

            <!-- Add to cart button -->
            <button 
              @click="addToCart(product.id)"
              :disabled="addingToCart === product.id || product.stock === 0"
              class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2"
            >
              <svg v-if="addingToCart === product.id" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span v-if="addingToCart === product.id">Agregando...</span>
              <span v-else-if="product.stock === 0">Sin Stock</span>
              <span v-else>Agregar al Carrito</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div v-else class="text-center py-8">
        <div class="text-gray-400 mb-4">
          <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
          </svg>
        </div>
        <p class="text-gray-600" v-if="currentCategoryName || searchQuery">
          No hay productos disponibles con los filtros aplicados.
        </p>
        <p class="text-gray-600" v-else>
          No hay productos disponibles en este momento.
        </p>
        <a href="/home" class="text-amber-600 hover:text-amber-700 text-sm font-medium mt-2 inline-block">
          Ver todos los productos
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onUnmounted, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import PromotionalProducts from '@/Components/PromotionalProducts.vue'; // ✅ IMPORTAR

// PRIMERO definir props
const props = defineProps({
  products: {
    type: Array,
    required: true,
    default: () => []
  },
  promotionalProducts: { // ✅ NUEVA PROP
    type: Array,
    default: () => []
  },
  categories: {
    type: Array,
    required: true,
    default: () => []
  },
  currentCategory: {
    type: Number,
    default: null
  },
  currentSearch: {
    type: String,
    default: ''
  },
  currentSort: {
    type: String,
    default: 'name'
  }
});

// LUEGO el debug de props
console.log('🔍 DEBUG - PROPS RECIBIDAS:', {
  productsCount: props.products.length,
  currentCategory: props.currentCategory,
  currentSearch: props.currentSearch,
  currentSort: props.currentSort
});

// Variables reactivas para filtros
// Inicializar valores reactivos
const searchQuery = ref(props.currentSearch || '');
const sortBy = ref(props.currentSort || 'name');
const currentCategory = ref(props.currentCategory ? parseInt(props.currentCategory) : null);
const isLoading = ref(false);
let searchTimeout = null;

// Computed para obtener el nombre de la categoría actual
const currentCategoryName = computed(() => {
  if (!currentCategory.value) return '';
  const category = props.categories.find(cat => cat.id === currentCategory.value);
  return category ? category.name : '';
});

// Watches para sincronizar props
watch(() => props.currentSearch, (newSearch) => {
  console.log('🔄 Sincronizando searchQuery:', newSearch);
  searchQuery.value = newSearch || '';
}, { immediate: true });

watch(() => props.currentSort, (newSort) => {
  console.log('🔄 Sincronizando sortBy:', newSort);
  sortBy.value = newSort || 'name';
}, { immediate: true });

watch(() => props.currentCategory, (newCategory) => {
  console.log('🔄 Sincronizando currentCategory:', newCategory);
  currentCategory.value = newCategory ? parseInt(newCategory) : null;
}, { immediate: true });

// Watches para debug
watch(() => props.products, (newProducts) => {
  console.log('🔍 DEBUG - PRODUCTOS ACTUALIZADOS:', newProducts.length);
}, { deep: true });

// DEBUG: Diagnóstico
onMounted(() => {
  console.log('🔍 PRODUCTLIST MOUNTED - PROPS:', {
    productsCount: props.products.length,
    currentCategory: props.currentCategory,
    currentSearch: props.currentSearch,
    currentSort: props.currentSort
  });
});

console.log('🎯 VALORES INICIALES:', {
  searchQuery: searchQuery.value,
  sortBy: sortBy.value,
  currentCategory: currentCategory.value
});

const addingToCart = ref(null);

// Manejar entrada de búsqueda
const handleSearchInput = () => {
  // El debounce ya está en updateFilters
  updateFilters();
};

// Actualizar filtros con debounce
const updateFilters = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
  
  isLoading.value = true;
  
  searchTimeout = setTimeout(() => {
    const params = new URLSearchParams();
    
    // Solo agregar parámetros con valor
    if (currentCategory.value) {
      params.set('category', currentCategory.value);
    }
    
    const searchTerm = searchQuery.value.trim();
    if (searchTerm) {
      params.set('search', searchTerm);
    }
    
    if (sortBy.value && sortBy.value !== 'name') {
      params.set('sort', sortBy.value);
    }

    const queryString = params.toString();
    const url = queryString ? `/home?${queryString}` : '/home';
    
    console.log('Navegando a:', url);
    
    // Usar window.location para forzar una recarga completa de la página
    window.location.href = url;
  }, 300); // 300ms de debounce
};

// Manejar clic en categoría
const handleCategoryClick = (categoryId) => {
  console.log('🎯 Categoría clickeada:', categoryId);
  currentCategory.value = categoryId === currentCategory.value ? null : categoryId;
  updateFilters();
};

// Texto descriptivo para ordenamiento
const getSortText = (sort) => {
  switch (sort) {
    case 'name': return 'A-Z';
    case 'name_desc': return 'Z-A';
    case 'price': return 'Precio Mayor';
    case 'price_desc': return 'Precio Menor';
    case 'stock': return 'Stock Mayor';
    default: return sort;
  }
};

// Agregar al carrito
const addToCart = async (productId) => {
  try {
    addingToCart.value = productId;
    await router.post('/cart/add', { product_id: productId, quantity: 1 }, {
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ['cartCount'] });
      },
      onError: () => {
        console.error('Error al agregar al carrito');
      },
      onFinish: () => {
        addingToCart.value = null;
      }
    });
  } catch (err) {
    console.error('Error adding to cart:', err);
    addingToCart.value = null;
  }
};

// Manejar error de imagen
const handleImageError = (event, product) => {
  event.target.onerror = null;
  event.target.src = '/images/placeholder.jpg';
  if (product) {
    product.image_url = '/images/placeholder.jpg';
  }
};

// ✅ NUEVA COMPUTED: Productos filtrados (excluyendo los que están en promoción)
const filteredProducts = computed(() => {
  // Obtener IDs de productos en promoción
  const promotionalProductIds = props.promotionalProducts.map(p => p.id);
  
  // Filtrar productos normales excluyendo los que están en promoción
  return props.products.filter(product => !promotionalProductIds.includes(product.id));
});

// ✅ NUEVA COMPUTED: Mostrar sección de promociones solo si hay promociones
const showPromotionalSection = computed(() => {
  return props.promotionalProducts && props.promotionalProducts.length > 0;
});

// Cleanup
onUnmounted(() => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
});
</script>