<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header (el mismo contenido) -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center">
          <a 
            :href="user && user.is_admin ? '/admin/dashboard' : '/home'" 
            class="text-2xl font-bold text-amber-600 flex items-center"
          >
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
            <!-- Navegación para usuarios normales -->
            <template v-if="user && !user.is_admin">
              <a href="/home" class="text-gray-700 hover:text-amber-600 px-3 py-2">Inicio</a>
              <a href="/cart" class="text-gray-700 hover:text-amber-600 px-3 py-2 flex items-center">
                <span>Carrito</span>
                <span v-if="$page.props.cartCount > 0" class="ml-1 bg-amber-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                  {{ $page.props.cartCount }}
                </span>
              </a>
              <a href="/orders" class="text-gray-700 hover:text-amber-600 px-3 py-2">Mis Pedidos</a>
            </template>
            <!-- Menú de usuario -->
            <div v-if="user" class="relative">
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
                <a 
                  href="/profile" 
                  class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                >
                  👤 Perfil
                </a>
                
                <div class="border-t border-gray-100"></div>
                <!-- ✅ SOLUCIÓN DEFINITIVA: Usar Inertia para logout -->
                <button 
                  @click="logout"
                  class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
                >
                  🚪 Cerrar Sesión
                </button>
              </div>
            </div>
            
            <!-- Enlaces para usuarios no autenticados -->
            <div v-else class="flex space-x-4">
              <a href="/login" class="text-gray-700 hover:text-amber-600 px-3 py-2">
                Ingresar
              </a>
              <a href="/register" class="text-gray-700 hover:text-amber-600 px-3 py-2">
                Registrarse
              </a>
            </div>
            <HelpLink />
          </nav>
        </div>
      </div>
    </header>

    <!-- Page Header (optional) -->
    <div v-if="$slots.pageHeader" class="bg-transparent">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <slot name="pageHeader" />
      </div>
    </div>

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
            <p class="text-gray-300 text-sm mb-2">
              {{ $page.props.shopSettings?.about_us || 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad.' }}
            </p>
          </div>
          
          <!-- Contacto -->
          <div class="text-center">
            <h3 class="text-lg font-semibold text-white mb-4">Contacto</h3>
            <div class="text-gray-300 text-sm space-y-1">
              <p>{{ $page.props.shopSettings?.phone || '+595 986 195914' }}</p>
              <p>{{ $page.props.shopSettings?.email || 'daylencoffee@gmail.com' }}</p>
              <p>{{ $page.props.shopSettings?.address || 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este' }}</p>
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
          <p class="text-white">&copy; {{ new Date().getFullYear() }} {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}. Todos los derechos reservados.</p>
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

// ✅ CORREGIDO: Usar usePage() para acceder a las props
const page = usePage()
const user = computed(() => page.props.auth.user)

const userName = computed(() => user.value?.name || 'Usuario')
const userInitial = computed(() => userName.value.charAt(0).toUpperCase())

// ✅ SOLUCIÓN DEFINITIVA: Logout con Inertia
const logout = () => {
  router.post('/logout')
}
</script>