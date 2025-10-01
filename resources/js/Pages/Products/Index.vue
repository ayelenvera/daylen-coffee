<template>
  <AppLayout title="Productos">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Productos
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-medium">Lista de Productos</h3>
            <Link 
              :href="route('products.create')" 
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Nuevo Producto
            </Link>
          </div>

          <div v-if="$page.props.flash.success" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ $page.props.flash.success }}
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="product in products" :key="product.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="h-16 w-16 overflow-hidden rounded-full">
                      <img 
                        :src="product.image_url" 
                        :alt="product.name"
                        class="h-full w-full object-cover"
                      >
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">{{ product.description || 'Sin descripción' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">₲ {{ Number(product.price || 0).toLocaleString('es-PY', { maximumFractionDigits: 0 }) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      :class="{
                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                        'bg-green-100 text-green-800': product.stock > 0,
                        'bg-red-100 text-red-800': product.stock <= 0
                      }"
                    >
                      {{ product.stock }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Link 
                      :href="route('products.edit', product.id)" 
                      class="text-indigo-600 hover:text-indigo-900 mr-3"
                    >
                      Editar
                    </Link>
                    <button 
                      @click="confirmDelete(product)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Eliminar
                    </button>
                  </td>
                </tr>
                <tr v-if="products.length === 0">
                  <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                    No hay productos registrados.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <DialogModal :show="confirmingProductDeletion" @close="closeModal">
      <template #title>
        Eliminar Producto
      </template>

      <template #content>
        ¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.
      </template>

      <template #footer>
        <SecondaryButton @click="closeModal">
          Cancelar
        </SecondaryButton>

        <DangerButton
          class="ml-3"
          :class="{ 'opacity-25': deleteForm.processing }"
          :disabled="deleteForm.processing"
          @click="deleteProduct"
        >
          Eliminar Producto
        </DangerButton>
      </template>
    </DialogModal>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
  products: {
    type: Array,
    required: true,
  },
});

const confirmingProductDeletion = ref(false);
const productToDelete = ref(null);

const deleteForm = useForm({});

const confirmDelete = (product) => {
  productToDelete.value = product;
  confirmingProductDeletion.value = true;
};

const deleteProduct = () => {
  deleteForm.delete(route('products.destroy', productToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
  });
};

const closeModal = () => {
  confirmingProductDeletion.value = false;
  productToDelete.value = null;
};
</script>
