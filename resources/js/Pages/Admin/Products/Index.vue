<template>
    <Head title="Crear Producto" />
    
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Crear Nuevo Producto
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <form @submit.prevent="form.post('/admin/products')" enctype="multipart/form-data">
                <div class="grid grid-cols-1 gap-6">
                  <!-- Nombre -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                    <input 
                      v-model="form.name"
                      type="text" 
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500"
                    />
                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
                  </div>
                  
                  <!-- Descripción -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea 
                      v-model="form.description"
                      rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500"
                    ></textarea>
                    <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</div>
                  </div>
                  
                  <!-- Precio -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Precio *</label>
                    <input 
                      v-model="form.price"
                      type="number" 
                      step="0.01"
                      min="0"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500"
                    />
                    <div v-if="form.errors.price" class="text-red-600 text-sm mt-1">{{ form.errors.price }}</div>
                  </div>
                  
                  <!-- Stock -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Stock *</label>
                    <input 
                      v-model="form.stock"
                      type="number" 
                      min="0"
                      required
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500"
                    />
                    <div v-if="form.errors.stock" class="text-red-600 text-sm mt-1">{{ form.errors.stock }}</div>
                  </div>
                  
                  <!-- Imagen -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Imagen</label>
                    <input 
                      type="file"
                      @input="form.image = $event.target.files[0]"
                      accept="image/*"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500"
                    />
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                    <div v-if="form.errors.image" class="text-red-600 text-sm mt-1">{{ form.errors.image }}</div>
                    
                    <!-- Vista previa -->
                    <div v-if="imagePreview" class="mt-2">
                      <img :src="imagePreview" class="h-20 w-20 object-cover rounded-lg border" />
                    </div>
                  </div>
                  
                  <!-- Botones -->
                  <div class="flex justify-end space-x-3 pt-4">
                    <Link 
                      href="/admin/products"
                      class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                    >
                      Cancelar
                    </Link>
                    <button 
                      type="submit"
                      :disabled="form.processing"
                      class="bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded"
                    >
                      {{ form.processing ? 'Creando...' : 'Crear Producto' }}
                    </button>
                  </div>
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
  import { ref, watch } from 'vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  const form = useForm({
    name: '',
    description: '',
    price: '',
    stock: '',
    image: null
  });
  
  const imagePreview = ref(null);
  
  watch(() => form.image, (file) => {
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreview.value = e.target.result;
      };
      reader.readAsDataURL(file);
    } else {
      imagePreview.value = null;
    }
  });
  </script>