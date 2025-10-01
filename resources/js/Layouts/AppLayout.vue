<!-- resources/js/Layouts/AppLayout.vue -->
<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header con logo dinámico -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center">
          <a href="/home" class="text-2xl font-bold text-amber-600 flex items-center">
            <span v-if="$page.props.shopSettings?.logo_url" class="mr-3">
              <img 
                :src="$page.props.shopSettings.logo_url" 
                :alt="$page.props.shopSettings?.shop_name || 'Daylen Cafetería'"
                class="h-8 w-8 object-contain"
              />
            </span>
            {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}
          </a>
          
          <!-- Enlaces de navegación básicos -->
          <nav class="flex space-x-4">
            <a href="/home" class="text-gray-700 hover:text-amber-600">
              Inicio
            </a>
            <template v-if="$page.props.auth.user">
              <a href="/profile" class="text-gray-700 hover:text-amber-600">
                Perfil
              </a>
              <form method="POST" action="/logout" class="inline">
                <button type="submit" class="text-gray-700 hover:text-amber-600">
                  Cerrar Sesión
                </button>
              </form>
            </template>
            <template v-else>
              <a href="/login" class="text-gray-700 hover:text-amber-600">
                Ingreso
              </a>
              <a href="/register" class="text-gray-700 hover:text-amber-600">
                Registro
              </a>
            </template>
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer dinámico -->
    <footer class="bg-gray-800 text-white mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Información de la tienda -->
          <div class="text-center md:text-left">
            <h3 class="text-lg font-semibold mb-4">
              {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}
            </h3>
            <p class="text-gray-300 text-sm mb-2">
              {{ $page.props.shopSettings?.about_us || 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad.' }}
            </p>
          </div>
          
          <!-- Contacto -->
          <div class="text-center">
            <h3 class="text-lg font-semibold mb-4">Contacto</h3>
            <div class="text-gray-300 text-sm space-y-1">
              <p>{{ $page.props.shopSettings?.phone || '+595 986 195914' }}</p>
              <p>{{ $page.props.shopSettings?.email || 'daylencoffee@gmail.com' }}</p>
              <p>{{ $page.props.shopSettings?.address || 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este' }}</p>
            </div>
          </div>
          
          <!-- Información legal -->
          <div class="text-center md:text-right">
            <h3 class="text-lg font-semibold mb-4">Información Legal</h3>
            <div class="text-gray-300 text-sm space-y-1">
              <p>RUC: {{ $page.props.shopSettings?.ruc || '12345678-9' }}</p>
            </div>
          </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-6 text-center">
          <p>&copy; {{ new Date().getFullYear() }} {{ $page.props.shopSettings?.shop_name || 'Daylen Cafetería' }}. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth.user)
</script>