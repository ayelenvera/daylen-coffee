<template>
    <component :is="layoutComponent">
      <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Header -->
          <div class="text-center mb-12">
            <div class="flex items-center justify-center mb-4">
              <span class="text-4xl mr-4">⚙️</span>
              <h1 class="text-4xl font-bold text-gray-900">Guía para Administradores</h1>
            </div>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
              Gestiona tu tienda eficientemente con estas herramientas y procesos
            </p>
          </div>
  
          <!-- Navegación Rápida -->
          <div class="bg-blue-50 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">Navegación Rápida</h2>
            <div class="flex flex-wrap gap-2">
              <a href="#gestion-productos" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                🛍️ Productos
              </a>
              <a href="#estadisticas" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                📊 Estadísticas
              </a>
              <a href="#pedidos" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                📦 Pedidos
              </a>
              <a href="#configuracion" class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
                ⚙️ Configuración
              </a>
            </div>
          </div>
  
          <!-- Sección 1: Gestión de Productos -->
          <section id="gestion-productos" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">🛍️</span>
                <h2 class="text-3xl font-bold text-gray-900">1. Gestión de Productos</h2>
              </div>
              
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">1</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Acceso al Panel</h3>
                      <p class="text-gray-600">Inicia sesión como administrador y navega a "Administrar" → "Productos"</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">2</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Agregar Productos</h3>
                      <p class="text-gray-600">Usa "Agregar nuevo producto" para crear nuevos ítems en el catálogo</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">3</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Editar Productos</h3>
                      <p class="text-gray-600">Haz clic en "editar" en cualquier producto existente para modificarlo</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">4</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Actualizar Información</h3>
                      <p class="text-gray-600">Modifica precios, descripciones, disponibilidad e imágenes según sea necesario</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">5</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Organizar Categorías</h3>
                      <p class="text-gray-600">Asigna productos a categorías para mejor organización y navegación</p>
                    </div>
                  </div>
                </div>
                
                <div class="bg-gray-100 rounded-lg overflow-hidden h-86 flex items-center justify-center">
                    <img 
                        src="/images/help/gestion-productos.png" 
                        alt="Gestión de productos en panel administrativo"
                        class="w-full h-full object-contain"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="text-center text-gray-500 flex flex-col items-center justify-center" style="display: none;">
                        <span class="text-4xl mb-2">📸</span>
                        <p>Captura de gestión de productos</p>
                        <p class="text-sm mt-2">(Imagen no disponible)</p>
                    </div>
                </div>

              </div>
            </div>
          </section>
  
          <!-- Sección 2: Estadísticas -->
          <section id="estadisticas" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">📊</span>
                <h2 class="text-3xl font-bold text-gray-900">2. Estadísticas de Ventas</h2>
              </div>
              
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                  <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">1</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Acceso al Dashboard</h3>
                      <p class="text-gray-600">Haz clic en "Administrar" en el menú principal</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">2</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Navega a Estadísticas</h3>
                      <p class="text-gray-600">Selecciona la sección "Estadísticas" del menú desplegable</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">3</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Métricas Disponibles</h3>
                      <p class="text-gray-600">Verás métricas clave como: cantidad de pedidos, clientes registrados, productos vendidos, nuevos pedidos</p>
                    </div>
                  </div>
                  
                  <div class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-4 flex-shrink-0">4</span>
                    <div>
                      <h3 class="font-semibold text-gray-900 mb-2">Filtros y Períodos</h3>
                      <p class="text-gray-600">Utiliza filtros por fecha para analizar tendencias específicas</p>
                    </div>
                  </div>
                </div>
                
                <div class="space-y-4">
                  <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">📈 Métricas Principales</h4>
                    <ul class="text-blue-800 text-sm space-y-2">
                      <li class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        <span><strong>Pedidos Totales:</strong> Número total de pedidos realizados</span>
                      </li>
                      <li class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        <span><strong>Clientes Activos:</strong> Usuarios registrados en el sistema</span>
                      </li>
                      <li class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        <span><strong>Productos en Stock:</strong> Inventario disponible</span>
                      </li>
                      <li class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        <span><strong>Pedidos:</strong> Total de ventas realizadas</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Sección 3: Manejo de Pedidos -->
          <section id="pedidos" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">📦</span>
                <h2 class="text-3xl font-bold text-gray-900">3. Manejo de Pedidos</h2>
              </div>
              
              <div class="space-y-8">
                <!-- Proceso de gestión de pedidos -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                  <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                      <span class="text-green-600 font-bold">1</span>
                    </div>
                    <h4 class="font-semibold text-green-900 mb-2">Acceder</h4>
                    <p class="text-green-700 text-sm">Ve a "Pedidos" en Administrar</p>
                  </div>
                  
                  <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                      <span class="text-green-600 font-bold">2</span>
                    </div>
                    <h4 class="font-semibold text-green-900 mb-2">Revisar</h4>
                    <p class="text-green-700 text-sm">Los nuevos pedidos aparecen como "Pendientes"</p>
                  </div>
                  
                  <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                      <span class="text-green-600 font-bold">3</span>
                    </div>
                    <h4 class="font-semibold text-green-900 mb-2">Detalles</h4>
                    <p class="text-green-700 text-sm">Revisa productos e información del cliente</p>
                  </div>
                  
                  <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                      <span class="text-green-600 font-bold">4</span>
                    </div>
                    <h4 class="font-semibold text-green-900 mb-2">Actualizar</h4>
                    <p class="text-green-700 text-sm">Cambia estado del pedido según progreso</p>
                  </div>
                </div>
                
                <!-- Estados de Pedidos -->
                <div class="bg-gray-50 rounded-lg p-6">
                  <h4 class="font-semibold text-gray-900 mb-4">Estados de Pedidos Disponibles:</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4 text-center">
                      <span class="text-yellow-600 font-bold text-sm">PENDIENTE</span>
                      <p class="text-yellow-700 text-xs mt-1">Pedido recibido, esperando confirmación</p>
                    </div>
                    <div class="bg-blue-100 border border-blue-300 rounded-lg p-4 text-center">
                      <span class="text-blue-600 font-bold text-sm">PAGADO</span>
                      <p class="text-blue-700 text-xs mt-1">Pago confirmado, en preparación</p>
                    </div>
                    <div class="bg-red-100 border border-red-300 rounded-lg p-4 text-center">
                      <span class="text-red-600 font-bold text-sm">CANCELADO</span>
                      <p class="text-red-700 text-xs mt-1">Pedido cancelado por cliente o sistema</p>
                    </div>
                    <div class="bg-green-100 border border-green-300 rounded-lg p-4 text-center">
                      <span class="text-green-600 font-bold text-sm">COMPLETADO</span>
                      <p class="text-green-700 text-xs mt-1">Pedido entregado exitosamente</p>
                    </div>
                  </div>
                </div>
                
                <!-- Gestión de Problemas -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                  <h4 class="font-semibold text-red-900 mb-3">🚨 Gestión de Problemas y Cancelaciones</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-red-800">
                    <div>
                      <h5 class="font-medium mb-2">Problemas Comunes:</h5>
                      <ul class="text-sm space-y-1">
                        <li>• Producto agotado</li>
                        <li>• Error en datos del cliente</li>
                        <li>• Problemas de pago</li>
                        <li>• Cambios de último momento</li>
                      </ul>
                    </div>
                    <div>
                      <h5 class="font-medium mb-2">Acciones:</h5>
                      <ul class="text-sm space-y-1">
                        <li>• Contactar al cliente</li>
                        <li>• Ofrecer alternativas</li>
                        <li>• Procesar reembolsos</li>
                        <li>• Actualizar estados</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Sección 4: Configuración de Tienda -->
          <section id="configuracion" class="mb-12">
            <div class="bg-white rounded-lg shadow-md p-8">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">⚙️</span>
                <h2 class="text-3xl font-bold text-gray-900">4. Configuración de la Tienda</h2>
              </div>
              
              <div class="space-y-6">
                <!-- Opciones de Configuración -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center mb-3">
                      <span class="text-purple-600 mr-2">🏪</span>
                      <h4 class="font-semibold text-purple-900">Información del Negocio</h4>
                    </div>
                    <ul class="text-purple-800 text-sm space-y-1">
                      <li>• Nombre de la tienda</li>
                      <li>• Logo personalizado</li>
                      <li>• Descripción</li>
                      <li>• Información de contacto</li>
                    </ul>
                  </div>
                  
                  <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center mb-3">
                      <span class="text-purple-600 mr-2">💳</span>
                      <h4 class="font-semibold text-purple-900">Métodos de Pago</h4>
                    </div>
                    <ul class="text-purple-800 text-sm space-y-1">
                      <li>• Efectivo</li>
                      <li>• Tarjetas</li>
                      <li>• Transferencias</li>
                      <li>• Otros métodos</li>
                    </ul>
                  </div>
                  
                  <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center mb-3">
                      <span class="text-purple-600 mr-2">🚚</span>
                      <h4 class="font-semibold text-purple-900">Zonas de Envío</h4>
                    </div>
                    <ul class="text-purple-800 text-sm space-y-1">
                      <li>• Configurar zonas</li>
                      <li>• Costos de envío</li>
                      <li>• Tiempos de entrega</li>
                    </ul>
                  </div>
                </div>
                
                <!-- Configuraciones Adicionales -->
                <div class="bg-gray-50 rounded-lg p-6">
                  <h4 class="font-semibold text-gray-900 mb-4">Configuraciones Adicionales:</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                        <span class="mr-2">📋</span>
                        Configuración de Impuestos
                      </h5>
                      <p class="text-gray-600 text-sm">Establece porcentajes de impuestos aplicables a los productos</p>
                    </div>
                    
                    <div>
                      <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                        <span class="mr-2">💬</span>
                        Mensajes Automáticos
                      </h5>
                      <p class="text-gray-600 text-sm">Personaliza mensajes de confirmación, estado y notificaciones</p>
                    </div>
                    
                    <div>
                      <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                        <span class="mr-2">🕒</span>
                        Horarios de Atención
                      </h5>
                      <p class="text-gray-600 text-sm">Define días y horarios en los que la tienda está disponible</p>
                    </div>
                    
                    <div>
                      <h5 class="font-medium text-gray-800 mb-2 flex items-center">
                        <span class="mr-2">🔥</span>
                        Promociones y Descuentos
                      </h5>
                      <p class="text-gray-600 text-sm">Configura ofertas especiales, códigos promocionales y descuentos</p>
                    </div>
                  </div>
                </div>
                
                <!-- Proceso de Configuración -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                  <h4 class="font-semibold text-blue-900 mb-3">📝 Proceso de Configuración:</h4>
                  <ol class="text-blue-800 space-y-2">
                    <li class="flex items-start">
                      <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">1</span>
                      <span>Accede a "Configuración de la tienda" desde el menú de Administrar</span>
                    </li>
                    <li class="flex items-start">
                      <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">2</span>
                      <span>Actualiza la información básica del negocio (nombre, logo, descripción)</span>
                    </li>
                    <li class="flex items-start">
                      <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">3</span>
                      <span>Configura métodos de pago aceptados según tu negocio</span>
                    </li>
                    <li class="flex items-start">
                      <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">4</span>
                      <span>Establece zonas de envío y sus respectivos costos</span>
                    </li>
                    <li class="flex items-start">
                      <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">5</span>
                      <span>Configura impuestos aplicables según tu región</span>
                    </li>
                    <li class="flex items-start">
                      <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">6</span>
                      <span>Personaliza mensajes automáticos para diferentes situaciones</span>
                    </li>
                    <li class="flex items-start">
                      <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">7</span>
                      <span>Gestiona horarios de atención para tu tienda</span>
                    </li>
                    <li class="flex items-start">
                      <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 flex-shrink-0">8</span>
                      <span>Configura promociones y descuentos especiales según temporadas</span>
                    </li>
                  </ol>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Sección de Consejos Adicionales -->
          <section class="mb-12">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md p-8 text-white">
              <div class="flex items-center mb-6">
                <span class="text-3xl mr-4">💡</span>
                <h2 class="text-3xl font-bold">Consejos para una Gestión Efectiva</h2>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                  <h4 class="font-semibold mb-2">🔄 Actualización Constante</h4>
                  <p class="text-blue-100 text-sm">Mantén el inventario y precios actualizados para evitar inconvenientes con los clientes</p>
                </div>
                
                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                  <h4 class="font-semibold mb-2">📱 Comunicación Clara</h4>
                  <p class="text-blue-100 text-sm">Responde rápidamente a las consultas y mantén informados a los clientes sobre el estado de sus pedidos</p>
                </div>
                
                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                  <h4 class="font-semibold mb-2">📊 Análisis Regular</h4>
                  <p class="text-blue-100 text-sm">Revisa las estadísticas semanalmente para identificar tendencias y oportunidades de mejora</p>
                </div>
                
                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                  <h4 class="font-semibold mb-2">🛡️ Seguridad</h4>
                  <p class="text-blue-100 text-sm">Mantén tus credenciales seguras y realiza backups periódicos de la información</p>
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