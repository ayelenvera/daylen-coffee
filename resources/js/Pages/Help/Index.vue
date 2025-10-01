<template>
    <component :is="layoutComponent">
      <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Header -->
          <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Centro de Ayuda</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
              Encuentra respuestas a todas tus preguntas y aprende a usar nuestro sistema
            </p>
          </div>
  
          <!-- Tarjetas de Navegación -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Guía para Usuarios -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition-shadow">
              <div class="flex items-center mb-4">
                <span class="text-3xl mr-4">👤</span>
                <h2 class="text-2xl font-semibold text-gray-800">Guía para Usuarios</h2>
              </div>
              <p class="text-gray-600 mb-4">
                Aprende a navegar, realizar pedidos y gestionar tu cuenta en nuestro sistema.
              </p>
              <ul class="text-gray-600 space-y-2 mb-6">
                <li>✅ Registro y autenticación</li>
                <li>✅ Navegación por el catálogo</li>
                <li>✅ Proceso de compra completo</li>
                <li>✅ Gestión de pedidos</li>
              </ul>
              <a 
                href="/help/user-guide" 
                class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors"
              >
                Ver Guía Completa
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </a>
            </div>
  
            <!-- Guía para Administradores -->
            <div v-if="isAdmin" class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition-shadow">
              <div class="flex items-center mb-4">
                <span class="text-3xl mr-4">⚙️</span>
                <h2 class="text-2xl font-semibold text-gray-800">Guía para Administradores</h2>
              </div>
              <p class="text-gray-600 mb-4">
                Herramientas y procesos para gestionar tu tienda eficientemente.
              </p>
              <ul class="text-gray-600 space-y-2 mb-6">
                <li>✅ Gestión de productos</li>
                <li>✅ Control de pedidos</li>
                <li>✅ Estadísticas y reportes</li>
                <li>✅ Configuración de tienda</li>
              </ul>
              <a 
                href="/help/admin-guide" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              >
                Ver Guía Administrativa
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </a>
            </div>
          </div>
  
          <!-- Sección de Video Tutorial -->
          <div class="bg-gray-50 rounded-lg p-8 mb-12">
            <div class="text-center mb-6">
              <h2 class="text-3xl font-bold text-gray-900 mb-2">Video Tutorial</h2>
              <p class="text-gray-600">Aprende visualmente cómo usar nuestro sistema</p>
            </div>
            <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
              <div class="text-center">
                <span class="text-6xl mb-4">🎥</span>
                <p class="text-gray-600">Video tutorial próximamente</p>
                <p class="text-sm text-gray-500 mt-2">Estamos preparando un video completo para ti</p>
              </div>
            </div>
          </div>
  
          <!-- Preguntas Frecuentes -->
          <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Preguntas Frecuentes</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <!-- FAQs para Usuarios -->
              <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                  <span class="mr-2">👤</span>
                  Para Usuarios
                </h3>
                <div class="space-y-4">
                  <div class="border-b border-gray-200 pb-4">
                    <h4 class="font-medium text-gray-900 mb-2">¿Cómo me registro en el sistema?</h4>
                    <p class="text-gray-600 text-sm">Haz clic en "Registrarse" en la esquina superior derecha y completa el formulario con tus datos.</p>
                  </div>
                  <div class="border-b border-gray-200 pb-4">
                    <h4 class="font-medium text-gray-900 mb-2">¿Puedo modificar mi pedido después de realizarlo?</h4>
                    <p class="text-gray-600 text-sm">Los pedidos solo pueden modificarse antes del pago. Contacta al administrador para cambios posteriores.</p>
                  </div>
                  <div class="border-b border-gray-200 pb-4">
                    <h4 class="font-medium text-gray-900 mb-2">¿Cómo sé el estado de mi pedido?</h4>
                    <p class="text-gray-600 text-sm">En "Mis Pedidos" puedes ver el estado actual de cada pedido en tiempo real.</p>
                  </div>
                </div>
              </div>
  
              <!-- FAQs para Administradores -->
              <div v-if="isAdmin">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                  <span class="mr-2">⚙️</span>
                  Para Administradores
                </h3>
                <div class="space-y-4">
                  <div class="border-b border-gray-200 pb-4">
                    <h4 class="font-medium text-gray-900 mb-2">¿Cómo agregar un nuevo producto?</h4>
                    <p class="text-gray-600 text-sm">Ve a "Administrar" → "Productos" y usa el botón "Agregar nuevo producto".</p>
                  </div>
                  <div class="border-b border-gray-200 pb-4">
                    <h4 class="font-medium text-gray-900 mb-2">¿Cómo actualizar el stock?</h4>
                    <p class="text-gray-600 text-sm">En la gestión de productos, edita cada producto y actualiza la cantidad disponible.</p>
                  </div>
                  <div class="border-b border-gray-200 pb-4">
                    <h4 class="font-medium text-gray-900 mb-2">¿Dónde veo las estadísticas de ventas?</h4>
                    <p class="text-gray-600 text-sm">En el panel de administración, ve a "Estadísticas" para ver reportes detallados.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </component>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  import { usePage } from '@inertiajs/vue3'
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  import AdminLayout from '@/Layouts/AdminLayout.vue'
  import GuestLayout from '@/Layouts/GuestLayout.vue'
  
  const page = usePage()
  
  // Determinar el layout basado en el tipo de usuario
  const layoutComponent = computed(() => {
    const user = page.props.auth.user
    
    if (!user) {
      return GuestLayout
    }
    
    return user.is_admin ? AdminLayout : AuthenticatedLayout
  })
  
  // Verificar si es administrador
  const isAdmin = computed(() => page.props.auth.user?.is_admin || false)
  </script>