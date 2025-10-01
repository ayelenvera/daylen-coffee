<!-- resources/js/Pages/Profile/Edit.vue -->
<template>
  <Head title="Perfil" />

  <component :is="layoutComponent">
    <div class="max-w-2xl mx-auto mt-12">
      <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
          <h2 class="text-xl font-semibold text-gray-800">Editar Perfil</h2>
          <p class="text-sm text-gray-600 mt-1">Actualiza tu información personal</p>
        </div>

        <div class="p-6">
          <form @submit.prevent="submit">
            <!-- Información personal -->
            <div class="mb-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
              
              <div class="grid grid-cols-1 gap-4">
                <!-- Nombre -->
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                  <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    :class="{ 'border-red-500': form.errors.name }"
                  >
                  <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    :class="{ 'border-red-500': form.errors.email }"
                  >
                  <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>
              </div>
            </div>

            <!-- Contraseña -->
            <div class="mb-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Cambiar Contraseña</h3>
              
              <div class="grid grid-cols-1 gap-4">
                <!-- Contraseña actual -->
                <div>
                  <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                  <input
                    id="current_password"
                    v-model="form.current_password"
                    type="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    :class="{ 'border-red-500': form.errors.current_password }"
                  >
                  <p v-if="form.errors.current_password" class="text-red-500 text-sm mt-1">{{ form.errors.current_password }}</p>
                </div>

                <!-- Nueva contraseña -->
                <div>
                  <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                  <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    :class="{ 'border-red-500': form.errors.password }"
                  >
                  <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>

                <!-- Confirmar contraseña -->
                <div>
                  <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                  <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                    :class="{ 'border-red-500': form.errors.password_confirmation }"
                  >
                  <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
                </div>
              </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between">
              <button type="button" @click="goHome" class="text-amber-600 hover:text-amber-700 font-medium flex items-center">
                ← Volver al Inicio
              </button>
              
              <div class="flex space-x-3">
                <button type="button" @click="goBack" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-medium">
                  Cancelar
                </button>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="px-4 py-2 bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white rounded-md font-medium"
                >
                  {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </component>
</template>

<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


const page = usePage()
const layoutComponent = computed(() => {
  const isAdmin = page.props.auth?.user?.is_admin
  return isAdmin ? AdminLayout : AuthenticatedLayout
})
const goHome = () => {
  const isAdmin = page.props.auth?.user?.is_admin
  router.visit(isAdmin ? '/admin/dashboard' : '/home')
}

const goBack = () => {
  if (window.history.length > 1) {
    window.history.back()
  } else {
    goHome()
  }
}


const props = defineProps({
  user: Object,
})

const form = useForm({
  name: props.user?.name || '',
  email: props.user?.email || '',
  current_password: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  // Limpiar errores previos de contraseñas
  form.clearErrors()

  // Validaciones cliente
  if (form.password) {
    if (form.password.length < 8) {
      form.setError('password', 'La contraseña debe tener al menos 8 caracteres.')
      return
    }
    if (form.password !== form.password_confirmation) {
      form.setError('password_confirmation', 'Las contraseñas no coinciden.')
      return
    }
  }

  const data = new FormData()
  data.append('name', form.name)
  data.append('email', form.email)
  if (form.current_password) data.append('current_password', form.current_password)
  if (form.password) data.append('password', form.password)
  if (form.password_confirmation) data.append('password_confirmation', form.password_confirmation)
  data.append('_method', 'PATCH')

  form.processing = true
  router.post('/profile', data, {
    onFinish: () => {
      form.processing = false
      form.reset('password', 'current_password', 'password_confirmation')
    }
  })
}

const resetForm = () => {
  form.reset()
  form.name = props.user?.name || ''
  form.email = props.user?.email || ''
}
</script>