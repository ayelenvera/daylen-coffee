<template>
  <Head title="Crear Nuevo Producto" />
  
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-amber-600">➕</span> Crear Nuevo Producto
          </h2>
          <p class="text-sm text-gray-600 mt-1">Agrega un nuevo producto al catálogo</p>
        </div>
        <Link 
          :href="route('admin.products')"
          class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg flex items-center space-x-2 transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          <span>Volver</span>
        </Link>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Card principal -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
          <!-- Header de la card -->
          <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-amber-100">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                <span class="text-amber-600 text-lg">☕</span>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Información del Producto</h3>
                <p class="text-sm text-gray-600">Completa todos los campos requeridos</p>
              </div>
            </div>
          </div>
          
          <!-- Formulario -->
          <div class="p-8">
            <form @submit.prevent="submitForm" enctype="multipart/form-data" class="space-y-8">
              <!-- Sección 1: Información básica -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Columna izquierda -->
                <div class="space-y-6">
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                      <span class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-2">
                        <span class="text-amber-600 text-sm">1</span>
                      </span>
                      Información Básica
                    </h4>
                    
                    <!-- Nombre -->
                    <div class="mb-6">
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Nombre del Producto
                      </label>
                      <input 
                        v-model="form.name" 
                        type="text" 
                        required
                        placeholder="Ej: Cappuccino Especial"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                      />
                      <div v-if="form.errors.name" class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        {{ form.errors.name }}
                      </div>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="mb-6">
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                      </label>
                      <textarea 
                        v-model="form.description"
                        rows="4"
                        placeholder="Describe el producto..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                      ></textarea>
                      <div v-if="form.errors.description" class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        {{ form.errors.description }}
                      </div>
                    </div>
                    
                    <!-- Categoría -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Categoría
                      </label>
                      <select 
                        v-model="form.category"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                      >
                        <option value="">Selecciona una categoría</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                          {{ category.emoji }} {{ category.name }}
                        </option>
                      </select>
                      <div v-if="form.errors.category" class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        {{ form.errors.category }}
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Columna derecha -->
                <div class="space-y-6">
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                      <span class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-2">
                        <span class="text-amber-600 text-sm">2</span>
                      </span>
                      Precio y Stock
                    </h4>
                    
                    <!-- Precio -->
                    <div class="mb-6">
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Precio (₲)
                      </label>
                      <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <span class="text-gray-500">₲</span>
                        </div>
                        <input 
                          v-model="form.price"
                          type="number" 
                          step="100"
                          min="0"
                          required
                          placeholder="0"
                          class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                        />
                      </div>
                      <div v-if="form.errors.price" class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        {{ form.errors.price }}
                      </div>
                    </div>
                    
                    <!-- Stock -->
                    <div class="mb-6">
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Stock disponible
                      </label>
                      <input 
                        v-model="form.stock"
                        type="number" 
                        min="0"
                        required
                        placeholder="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                      />
                      <div v-if="form.errors.stock" class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        {{ form.errors.stock }}
                      </div>
                    </div>
                  </div>
                  
                  <!-- Imagen -->
                  <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                      <span class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-2">
                        <span class="text-amber-600 text-sm">3</span>
                      </span>
                      Imagen del Producto
                    </h4>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Subir imagen
                      </label>
                      <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-amber-400 transition-colors">
                        <input 
                          type="file"
                          @change="handleImageUpload"
                          accept="image/*"
                          class="hidden"
                          id="image-upload"
                        />
                        <label for="image-upload" class="cursor-pointer">
                          <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                          </svg>
                          <p class="text-sm text-gray-600">Haz clic para subir una imagen</p>
                          <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF hasta 2MB</p>
                        </label>
                      </div>
                      
                      <!-- Vista previa -->
                      <div v-if="imagePreview" class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Vista previa:</p>
                        <div class="relative inline-block">
                          <img :src="imagePreview" class="h-32 w-32 object-cover rounded-lg border-2 border-amber-200" />
                          <button 
                            @click="removeImage"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                          >
                            ×
                          </button>
                        </div>
                      </div>
                      
                      <div v-if="form.errors.image" class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        {{ form.errors.image }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Botones -->
              <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <Link 
                  :href="route('admin.products')"
                  class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors flex items-center space-x-2"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  <span>Cancelar</span>
                </Link>
                <button 
                  type="submit"
                  :disabled="form.processing"
                  :class="['px-6 py-3 font-medium rounded-lg transition-colors flex items-center space-x-2',
                          form.processing ? 'bg-gray-400 cursor-not-allowed' : 'bg-amber-600 hover:bg-amber-700']"
                >
                  <svg v-if="form.processing" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  <span class="text-white">{{ form.processing ? 'Creando...' : 'Crear Producto' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  categories: {
    type: Array,
    required: true,
    default: () => []
  }
});

const form = useForm({
  name: '',
  description: '',
  price: '',
  stock: '',
  category: '',
  image: null
});

const imagePreview = ref(null);

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const removeImage = () => {
  form.image = null;
  imagePreview.value = null;
  // Reset file input
  const fileInput = document.getElementById('image-upload');
  if (fileInput) fileInput.value = '';
};

const submitForm = () => {
  form.post(route('admin.products.store'), {
    onSuccess: () => {
      // Redirigir a la lista de productos
      window.location.href = route('admin.products');
    },
    onError: (errors) => {
      console.log('Errores del formulario:', errors);
    }
  });
};
</script>

<style scoped>
/* Estilos personalizados */
</style>