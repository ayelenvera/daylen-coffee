<template>
  <AppLayout title="Nuevo Producto">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Nuevo Producto
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Left Column -->
              <div>
                <!-- Name -->
                <div class="mb-4">
                  <InputLabel for="name" value="Nombre del Producto" />
                  <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                  />
                  <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Description -->
                <div class="mb-4">
                  <InputLabel for="description" value="Descripción" />
                  <TextArea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    class="mt-1 block w-full"
                  />
                  <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <!-- Price -->
                <div class="mb-4">
                  <InputLabel for="price" value="Precio" />
                  <div class="relative mt-1 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                      <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <TextInput
                      id="price"
                      v-model="form.price"
                      type="number"
                      step="0.01"
                      min="0"
                      class="block w-full pl-7"
                      required
                    />
                  </div>
                  <InputError :message="form.errors.price" class="mt-2" />
                </div>

                <!-- Stock -->
                <div class="mb-4">
                  <InputLabel for="stock" value="Cantidad en Stock" />
                  <TextInput
                    id="stock"
                    v-model.number="form.stock"
                    type="number"
                    min="0"
                    class="mt-1 block w-full"
                    required
                  />
                  <InputError :message="form.errors.stock" class="mt-2" />
                </div>
              </div>

              <!-- Right Column -->
              <div>
                <!-- Image URL -->
                <div class="mb-4">
                  <InputLabel for="image" value="URL de la Imagen" />
                  <TextInput
                    id="image"
                    v-model="form.image"
                    type="url"
                    class="mt-1 block w-full"
                    placeholder="https://ejemplo.com/imagen.jpg"
                  />
                  <p class="mt-1 text-sm text-gray-500">
                    Ingresa la URL de la imagen del producto
                  </p>
                  <InputError :message="form.errors.image" class="mt-2" />
                  
                  <!-- Image Preview -->
                  <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Vista previa:</p>
                    <div class="w-32 h-32 rounded-md overflow-hidden bg-gray-100 border border-gray-300">
                      <img 
                        :src="form.image || '/images/placeholder.jpg'" 
                        alt="Vista previa"
                        class="w-full h-full object-cover"
                        @error="$event.target.src = '/images/placeholder.jpg'"
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end mt-6">
              <Link 
                :href="route('products.index')" 
                class="text-gray-600 hover:text-gray-900 mr-4"
              >
                Cancelar
              </Link>
              <PrimaryButton 
                type="submit" 
                :class="{ 'opacity-25': form.processing }" 
                :disabled="form.processing"
              >
                Guardar Producto
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';

const form = useForm({
  name: '',
  description: '',
  price: 0,
  stock: 0,
  image: '',
});

const submit = () => {
  form.post(route('products.store'), {
    onSuccess: () => {
      form.reset();
    },
  });
};
</script>
