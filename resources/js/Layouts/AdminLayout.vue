<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center">
          <a href="/admin/dashboard" class="text-2xl font-bold text-amber-600 flex items-center">
            <span v-if="$page.props.shopSettings?.logo_url" class="mr-3">
              <img 
                :src="$page.props.shopSettings.logo_url" 
                :alt="$page.props.shopSettings?.shop_name || 'Daylen Cafetería'"
                class="h-8 w-8 object-contain"
              />
            </span>
            {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}
          </a>

          <nav class="flex space-x-4 items-center">
            <a href="/admin/dashboard" class="text-gray-700 hover:text-amber-600 px-3 py-2">
              Inicio
            </a>
            
            <!-- Menú de administración -->
            <div class="relative group">
              <button class="text-gray-700 hover:text-amber-600 px-3 py-2 flex items-center">
                <span>Administrar</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              <div class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                <!-- Agregar esta línea: -->
                <a href="/admin/shop-settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  ⚙️ Configuración Tienda
                </a>
                <a href="/admin/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  📊 Estadísticas
                </a>
                <a href="/admin/products" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  🛍️ Productos
                </a>
                <a href="/admin/promotions" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  🔥 Promociones
                </a>
                <a href="/admin/customization-rules" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  ⚙️ Personalización
                </a>
                <a href="/admin/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  📦 Pedidos
                </a>
              </div>
            </div>
            <!-- Menú de usuario -->
            <div class="relative">
              <button 
                @click="showUserMenu = !showUserMenu"
                class="flex items-center space-x-2 text-gray-700 hover:text-amber-600"
              >
                <span class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                  <span class="text-amber-600 font-medium">
                    {{ userInitial }}
                  </span>
                </span>
                <span class="">{{ userName }}</span>
              </button>

              <!-- Dropdown menu -->
              <div 
                v-show="showUserMenu"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
              >
                <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                  👤 Perfil
                </a>
                
                <div class="border-t border-gray-100"></div>
                <button 
                  @click="logout"
                  class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
                >
                  🚪 Cerrar Sesión
                </button>
              </div>
            </div>
            <HelpLink />
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Información de la tienda -->
          <div class="text-center md:text-left">
            <h3 class="text-lg font-semibold text-white mb-4">
              {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}
            </h3>
            <p class="text-gray-300 text-sm">
              Panel de Administración
            </p>
          </div>
          
          <!-- Contacto -->
          <div class="text-center">
            <h3 class="text-lg font-semibold text-white mb-4">Contacto</h3>
            <div class="text-gray-300 text-sm space-y-1">
              <p>{{ $page.props.shopSettings?.phone || '+595 986 195914' }}</p>
              <p>{{ $page.props.shopSettings?.email || 'daylencoffee@gmail.com' }}</p>
            </div>
          </div>
          
          <!-- Información legal -->
          <div class="text-center md:text-right">
            <h3 class="text-lg font-semibold text-white mb-4">Información Legal</h3>
            <div class="text-gray-300 text-sm space-y-1">
              <p>RUC: {{ $page.props.shopSettings?.ruc || '12345678-9' }}</p>
            </div>
          </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-6 text-center">
          <p class="text-white">&copy; {{ new Date().getFullYear() }} {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}. Panel de Administración.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import HelpLink from '@/Components/HelpLink.vue';
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const showUserMenu = ref(false)

// Obtener el usuario desde las props compartidas de Inertia
const page = usePage()
const user = computed(() => page.props.auth?.user || null)

const userName = computed(() => user.value?.name || 'Usuario')
const userInitial = computed(() => userName.value.charAt(0).toUpperCase())

// Logout con Inertia para limpiar correctamente la sesión del guard web
const logout = () => {
  router.post('/logout')
}
</script>