<template>
    <component :is="layoutComponent">
      <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Header -->
          <div class="text-center mb-12">
            <div class="flex items-center justify-center mb-4">
              <span class="text-4xl mr-4">👤</span>
              <h1 class="text-4xl font-bold text-gray-900">Guía para Usuarios</h1>
            </div>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
              Aprende a usar todas las funciones del sistema paso a paso
            </p>
          </div>
  
          <!-- Navegación Rápida -->
          <div class="bg-blue-50 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">Navegación Rápida</h2>
            <div class="flex flex-wrap gap-2">
              <a href="#registro" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                📝 Registro
              </a>
              <a href="#navegacion" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                🧭 Navegación
              </a>
              <a href="#carrito" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                🛒 Carrito
              </a>
              <a href="#pedidos" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                📦 Pedidos
              </a>
              <a href="#historial" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                📋 Historial
              </a>
            </div>
          </div>
  
          <!-- Sección 1: Registro -->
          <section id="registro" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">📝</span>
                <h2 class="text-3xl font-bold text-gray-900">1. Cómo Registrarse en el Sistema</h2>
              </div>
              
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">1</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Haz clic en "Registrarse"</h3>
                      <p class="text-gray-600">Encuentra el botón de registro en la esquina superior derecha de la pantalla</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">2</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Completa el formulario</h3>
                      <p class="text-gray-600">Ingresa tus datos personales: nombre, email, contraseña y información de contacto</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">3</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Verifica tu correo</h3>
                      <p class="text-gray-600">Si es requerido, revisa tu bandeja de entrada para confirmar tu cuenta</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">4</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Inicia sesión</h3>
                      <p class="text-gray-600">Usa tus nuevas credenciales para acceder al sistema</p>
                    </div>
                  </div>
                </div>
                <div class="bg-gray-100 rounded-lg overflow-hidden h-64 flex items-center justify-center">
                    <img 
                        src="/images/help/registro-formulario.png" 
                        alt="Formulario de registro del sistema"
                        class="w-full h-full object-contain"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="text-center text-gray-500 flex flex-col items-center justify-center" style="display: none;">
                        <span class="text-4xl mb-2">📱</span>
                        <p>Captura de pantalla del formulario de registro</p>
                        <p class="text-sm mt-2">(Imagen no disponible)</p>
                    </div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Sección 2: Navegación -->
          <section id="navegacion" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">🧭</span>
                <h2 class="text-3xl font-bold text-gray-900">2. Cómo Navegar por el Catálogo</h2>
              </div>
              
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                  <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">A</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Usa las categorías</h3>
                      <p class="text-gray-600">Explora productos por categorías en la barra lateral o menú principal</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">B</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Barra de búsqueda</h3>
                      <p class="text-gray-600">Encuentra productos específicos usando palabras clave</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">C</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Filtros avanzados</h3>
                      <p class="text-gray-600">Filtra por categorías, precios, características o disponibilidad</p>
                    </div>
                  </div>
                </div>
                <div class="bg-gray-100 rounded-lg overflow-hidden h-64 flex items-center justify-center">
                    <img 
                        src="/images/help/catalogo-filtros.png" 
                        alt="Catálogo y filtros del sistema"
                        class="w-full h-full object-contain"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="text-center text-gray-500 flex flex-col items-center justify-center" style="display: none;">
                        <span class="text-4xl mb-2">🔍</span>
                        <p>Captura del catálogo y filtros</p>
                        <p class="text-sm mt-2">(Imagen no disponible)</p>
                    </div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Sección 3: Carrito -->
          <section id="carrito" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">🛒</span>
                <h2 class="text-3xl font-bold text-gray-900">3. Cómo Agregar Productos al Carrito</h2>
              </div>
              
              <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div class="text-center p-4 bg-amber-50 rounded-lg">
                    <span class="text-2xl mb-2 block">1️⃣</span>
                    <h3 class="font-semibold mb-2">Selecciona el producto</h3>
                    <p class="text-sm text-gray-600">¡Elige un producto delicioso entre las variedades!</p>
                  </div>
                  
                  <div class="text-center p-4 bg-amber-50 rounded-lg">
                    <span class="text-2xl mb-2 block">2️⃣</span>
                    <h3 class="font-semibold mb-2">Agrega al carrito</h3>
                    <p class="text-sm text-gray-600">Haz clic en "Agregar al carrito" en la tarjeta del producto</p>
                  </div>
                  
                  <div class="text-center p-4 bg-amber-50 rounded-lg">
                    <span class="text-2xl mb-2 block">3️⃣</span>
                    <h3 class="font-semibold mb-2">Visualizar Carrito</h3>
                    <p class="text-sm text-gray-600">Haz clic en "Ver Carrito" para personalizar tus productos</p>
                  </div>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                  <div class="flex items-start">
                    <span class="text-green-600 mr-3">💡</span>
                    <div>
                      <h4 class="font-semibold text-green-900 mb-1">Tip Importante</h4>
                      <p class="text-green-800 text-sm">El contador del carrito se actualizará automáticamente mostrando la cantidad de items agregados</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Sección 4: Realizar Pedido -->
          <section id="pedidos" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">📦</span>
                <h2 class="text-3xl font-bold text-gray-900">4. Cómo Realizar un Pedido</h2>
              </div>
              
              <div class="space-y-8">
                <!-- Paso a paso del proceso de pedido -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                      <span class="text-purple-600 font-bold">1</span>
                    </div>
                    <h4 class="font-semibold text-purple-900 mb-2">Ve al Carrito</h4>
                    <p class="text-purple-700 text-sm">Accede a tu carrito de compras</p>
                  </div>
                  
                  <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                      <span class="text-purple-600 font-bold">2</span>
                    </div>
                    <h4 class="font-semibold text-purple-900 mb-2">Revisa y Personaliza</h4>
                    <p class="text-purple-700 text-sm">Verifica productos y personaliza tu pedido</p>
                  </div>
                  
                  <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                      <span class="text-purple-600 font-bold">3</span>
                    </div>
                    <h4 class="font-semibold text-purple-900 mb-2">Método de Pago</h4>
                    <p class="text-purple-700 text-sm">Selecciona cómo deseas pagar</p>
                  </div>
                  
                  <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                      <span class="text-purple-600 font-bold">4</span>
                    </div>
                    <h4 class="font-semibold text-purple-900 mb-2">Confirma Pedido</h4>
                    <p class="text-purple-700 text-sm">Revisa y confirma tu pedido</p>
                  </div>
                </div>
                
                <!-- Detalles del proceso -->
                <div class="bg-gray-50 rounded-lg p-6">
                  <h4 class="font-semibold text-gray-900 mb-4">Proceso Detallado:</h4>
                  <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                      <span class="text-green-500 mr-2">✓</span>
                      <span>Ve a tu carrito de compras desde el menú principal</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-500 mr-2">✓</span>
                      <span>Revisa los productos, cantidades y personalizaciones</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-500 mr-2">✓</span>
                      <span>En el carrito personaliza tu pedido si es necesario (tamaño, ingredientes, etc.)</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-500 mr-2">✓</span>
                      <span>Selecciona método de pago y dirección de envío</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-500 mr-2">✓</span>
                      <span>Confirma la dirección de entrega</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-500 mr-2">✓</span>
                      <span>Revisa el resumen final del pedido</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-500 mr-2">✓</span>
                      <span>Haz clic en "Confirmar pedido" para finalizar</span>
                    </li>
                    <li class="flex items-start">
                      <span class="text-green-500 mr-2">✓</span>
                      <span>Podrás visualizar tu factura con estados dinámicos del pedido</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Sección 5: Historial de Pedidos -->
          <section id="historial" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">📋</span>
                <h2 class="text-3xl font-bold text-gray-900">5. Cómo Ver el Historial de Pedidos</h2>
              </div>
              
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                  <div class="flex items-start">
                    <span class="bg-indigo-100 text-indigo-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">1</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Inicia sesión</h3>
                      <p class="text-gray-600">Accede a tu cuenta con tus credenciales</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-indigo-100 text-indigo-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">2</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Ve a "Mis Pedidos"</h3>
                      <p class="text-gray-600">Haz clic en esta opción del menú principal</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-indigo-100 text-indigo-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">3</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Revisa tu historial</h3>
                      <p class="text-gray-600">Verás una lista de todos tus pedidos anteriores ordenados por fecha</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-indigo-100 text-indigo-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">4</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Detalles del pedido</h3>
                      <p class="text-gray-600">Haz clic en cualquier pedido para ver productos, precios y estados</p>
                    </div>
                  </div>
                </div>
                
                <div class="space-y-4">
                  <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <h4 class="font-semibold text-indigo-900 mb-2">Estados del Pedido</h4>
                    <ul class="text-indigo-800 text-sm space-y-1">
                      <li>• <span class="font-medium">Pendiente:</span> Pedido recibido</li>
                      <li>• <span class="font-medium">Pagado:</span> Pedido en preparación</li>
                      <li>• <span class="font-medium">Cancelado:</span> Pedido cancelado</li>
                      <!-- <li>• <span class="font-medium">Completado:</span> Pedido finalizado</li> -->
                    </ul>
                  </div>
                  
                  <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="font-semibold text-red-900 mb-2">Gestión de Historial</h4>
                    <p class="text-red-800 text-sm">Puedes eliminar el historial de pedidos completados para mantener tu cuenta organizada</p>
                  </div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Botón de regreso -->
          <div class="text-center">
            <a 
              href="/help" 
              class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
              Volver al Centro de Ayuda
            </a>
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
  </script>