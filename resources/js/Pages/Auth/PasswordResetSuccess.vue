<template>
  <GuestLayout>
    <Head title="Contraseña Restablecida" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div class="text-center">
          <!-- Ícono de éxito -->
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          
          <h2 class="text-3xl font-extrabold text-gray-900 mb-4">
            ¡Contraseña Restablecida!
          </h2>
          
          <p class="text-lg text-gray-600 mb-8">
            Tu contraseña ha sido restablecida correctamente. Ya puedes iniciar sesión con tu nueva contraseña.
          </p>
        </div>

        <!-- Botones de acción -->
        <div class="space-y-4">
          <!-- Ir al Dashboard según el rol -->
          <button
            @click="goToDashboard"
            class="group relative flex w-full justify-center rounded-md bg-amber-600 px-3 py-3 text-sm font-semibold text-white hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 transition-colors duration-200"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
            </svg>
            Ir al Dashboard
          </button>

          <!-- Actualizar contraseña -->
          <button
            @click="updatePassword"
            class="group relative flex w-full justify-center rounded-md bg-amber-600 px-3 py-3 text-sm font-semibold text-white hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 transition-colors duration-200"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
            Actualizar Contraseña
          </button>

          <!-- Iniciar sesión -->
          <a
            href="/login"
            class="group relative flex w-full justify-center rounded-md bg-gray-600 px-3 py-3 text-sm font-semibold text-white hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 transition-colors duration-200"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Iniciar Sesión
          </a>
        </div>

        <!-- Información adicional -->
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-md">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-blue-800">
                Consejo de Seguridad
              </h3>
              <div class="mt-2 text-sm text-blue-700">
                <p>
                  Para mantener tu cuenta segura, te recomendamos usar una contraseña única y fuerte. 
                  Considera cambiar tu contraseña regularmente.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const props = defineProps({
  user: Object,
});

const goToDashboard = () => {
  // Determinar si es admin o usuario normal
  const isAdmin = props.user?.is_admin;
  router.visit(isAdmin ? '/admin/dashboard' : '/home');
};

const updatePassword = () => {
  router.visit('/profile');
};
</script>
