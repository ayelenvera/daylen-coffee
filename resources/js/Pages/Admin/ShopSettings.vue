<!-- resources/js/Pages/Admin/ShopSettings.vue -->
<template>
    <Head title="Configuración de Tienda" />
    <AdminLayout>
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ⚙️ Configuración de la Tienda
          </h2>
          <div class="flex space-x-3">
            <button 
              @click="resetForm"
              type="button"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
            >
              🔄 Restablecer
            </button>
            <button 
              @click="saveSettings"
              :disabled="saving"
              class="bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center space-x-2"
            >
              <svg v-if="saving" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ saving ? 'Guardando...' : '💾 Guardar Cambios' }}</span>
            </button>
          </div>
        </div>
      </template>
  
      <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
          <!-- Alertas -->
          <div v-if="successMessage" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            ✅ {{ successMessage }}
          </div>
          
          <div v-if="errorMessage" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            ❌ {{ errorMessage }}
          </div>
  
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <form @submit.prevent="saveSettings" class="space-y-8">
                <!-- Sección: Información Básica -->
                <div class="border-b border-gray-200 pb-8">
                  <h3 class="text-lg font-semibold text-gray-900 mb-6">📋 Información Básica</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre de la Tienda -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Tienda *
                      </label>
                      <input 
                        v-model="form.shop_name"
                        type="text"
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                        required
                        placeholder="Ej: Daylen Cafetería"
                      />
                    </div>
  
                    <!-- RUC -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        RUC *
                      </label>
                      <input 
                        v-model="form.ruc"
                        type="text"
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                        required
                        placeholder="Ej: 12345678-9"
                      />
                    </div>
                  </div>
                </div>
  
                <!-- Sección: Logo -->
                <div class="border-b border-gray-200 pb-8">
                  <h3 class="text-lg font-semibold text-gray-900 mb-6">🖼️ Logo de la Tienda</h3>
                  
                  <div class="flex flex-col md:flex-row items-start space-y-6 md:space-y-0 md:space-x-8">
                    <!-- Vista previa del logo -->
                    <div class="flex-shrink-0">
                      <label class="block text-sm font-medium text-gray-700 mb-3">Vista Previa</label>
                          <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50">
                            <img 
                              v-if="logoPreview"
                              :src="logoPreview" 
                              alt="Logo preview"
                              class="w-full h-full object-contain"
                            />
                            <div v-else class="text-center text-gray-400 p-4">
                              <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                              </svg>
                              <span class="text-xs">Sin logo</span>
                            </div>
                          </div>
                        </div>
  
                    <!-- Upload del logo -->
                    <div class="flex-1">
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Subir Nuevo Logo
                      </label>
                      <input 
                        type="file"
                        ref="logoInput"
                        @change="handleLogoUpload"
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100"
                      />
                      <p class="text-xs text-gray-500 mt-2">
                        Formatos aceptados: JPG, PNG, WebP. Tamaño máximo: 5MB.
                      </p>
                      
                      <!-- Logo actual si existe -->
                      <div v-if="$page.props.shopSettings?.logo_url && !logoFile" class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Logo actual:</p>
                        <div class="flex items-center space-x-3">
                          <img 
                            :src="$page.props.shopSettings.logo_url" 
                            alt="Logo actual"
                            class="w-12 h-12 object-contain"
                          />
                          <span class="text-sm text-gray-700">{{ $page.props.shopSettings.logo_url }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
  
                <!-- Sección: Información de Contacto -->
                <div class="border-b border-gray-200 pb-8">
                  <h3 class="text-lg font-semibold text-gray-900 mb-6">📞 Información de Contacto</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teléfono -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono *
                      </label>
                      <input 
                        v-model="form.phone"
                        type="text"
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                        required
                        placeholder="Ej: +595 986 195914"
                      />
                    </div>
  
                    <!-- Email -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email *
                      </label>
                      <input 
                        v-model="form.email"
                        type="email"
                        class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                        required
                        placeholder="Ej: daylencoffee@gmail.com"
                      />
                    </div>
                  </div>
  
                  <!-- Dirección -->
                  <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Dirección Completa *
                    </label>
                    <textarea 
                      v-model="form.address"
                      rows="3"
                      class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                      required
                      placeholder="Ej: Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este"
                    ></textarea>
                  </div>
                </div>
  
                <!-- Sección: Acerca de Nosotros -->
                <div class="border-b border-gray-200 pb-8">
                  <h3 class="text-lg font-semibold text-gray-900 mb-6">📝 Acerca de Nosotros</h3>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Descripción de la Tienda *
                    </label>
                    <textarea 
                      v-model="form.about_us"
                      rows="4"
                      class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                      required
                      placeholder="Describe tu cafetería, misión, valores, etc..."
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-2">
                      Esta descripción aparecerá en el footer de tu sitio web.
                    </p>
                  </div>
                </div>
  
                <!-- Sección: Colores de la Marca -->
                <div class="border-b border-gray-200 pb-8">
                  <h3 class="text-lg font-semibold text-gray-900 mb-6">🎨 Colores de la Marca</h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Color Primario -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Color Primario *
                      </label>
                      <div class="flex items-center space-x-3">
                        <input 
                          v-model="form.primary_color"
                          type="color"
                          class="w-16 h-10 border border-gray-300 rounded-md cursor-pointer"
                        />
                        <input 
                          v-model="form.primary_color"
                          type="text"
                          class="flex-1 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 font-mono text-sm"
                          placeholder="#d97706"
                          maxlength="7"
                        />
                      </div>
                      <p class="text-xs text-gray-500 mt-2">
                        Color principal para botones y elementos destacados
                      </p>
                    </div>
  
                    <!-- Color Secundario -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Color Secundario *
                      </label>
                      <div class="flex items-center space-x-3">
                        <input 
                          v-model="form.secondary_color"
                          type="color"
                          class="w-16 h-10 border border-gray-300 rounded-md cursor-pointer"
                        />
                        <input 
                          v-model="form.secondary_color"
                          type="text"
                          class="flex-1 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 font-mono text-sm"
                          placeholder="#f59e0b"
                          maxlength="7"
                        />
                      </div>
                      <p class="text-xs text-gray-500 mt-2">
                        Color secundario para hover states y elementos secundarios
                      </p>
                    </div>
                  </div>
  
                  <!-- Vista previa de colores -->
                  <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Vista Previa de Colores:</h4>
                    <div class="flex space-x-4">
                      <div class="flex items-center space-x-2">
                        <div 
                          class="w-6 h-6 rounded border border-gray-300"
                          :style="{ backgroundColor: form.primary_color }"
                        ></div>
                        <span class="text-sm text-gray-600">Primario</span>
                      </div>
                      <div class="flex items-center space-x-2">
                        <div 
                          class="w-6 h-6 rounded border border-gray-300"
                          :style="{ backgroundColor: form.secondary_color }"
                        ></div>
                        <span class="text-sm text-gray-600">Secundario</span>
                      </div>
                    </div>
                  </div>
                </div>
  
                <!-- Botones de acción -->
                <div class="flex justify-end space-x-4 pt-6">
                  <button 
                    type="button"
                    @click="resetForm"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200"
                  >
                    🔄 Restablecer
                  </button>
                  <button 
                    type="submit"
                    :disabled="saving"
                    class="bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center space-x-2"
                  >
                    <svg v-if="saving" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ saving ? 'Guardando...' : '💾 Guardar Cambios' }}</span>
                  </button>
                </div>
              </form>
            </div>
          </div>
  
          <!-- Vista previa en tiempo real -->
          <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">👀 Vista Previa</h3>
              <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
                <div class="flex items-center space-x-3 mb-4">
                  <div 
                    v-if="logoPreview || $page.props.shopSettings?.logo_url"
                    class="w-12 h-12 flex items-center justify-center"
                  >
                    <img 
                      :src="logoPreview || $page.props.shopSettings?.logo_url" 
                      :alt="form.shop_name"
                      class="w-full h-full object-contain"
                    />
                  </div>
                  <h4 class="text-xl font-bold" :style="{ color: form.primary_color }">
                    {{ form.shop_name || 'Nombre de Tienda' }}
                  </h4>
                </div>
                
                <p class="text-gray-600 mb-4">
                  {{ form.about_us || 'Descripción de la tienda aparecerá aquí...' }}
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                  <div>
                    <strong>Teléfono:</strong> {{ form.phone || '+595 986 195914' }}
                  </div>
                  <div>
                    <strong>Email:</strong> {{ form.email || 'daylencoffee@gmail.com' }}
                  </div>
                  <div>
                    <strong>RUC:</strong> {{ form.ruc || '12345678-9' }}
                  </div>
                </div>
                
                <div class="mt-4">
                  <strong>Dirección:</strong> 
                  <span class="text-gray-600">{{ form.address || 'Dirección de la tienda...' }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup>
  import { ref, reactive, onMounted } from 'vue'
  import { Head, router } from '@inertiajs/vue3'
  import AdminLayout from '@/Layouts/AdminLayout.vue'
  
  // Estado del formulario
  const form = reactive({
    shop_name: '',
    logo: null,
    email: '',
    phone: '',
    address: '',
    ruc: '',
    about_us: '',
    primary_color: '#d97706',
    secondary_color: '#f59e0b'
  })
  
  // Referencias y estado
  const logoInput = ref(null)
  const logoFile = ref(null)
  const logoPreview = ref(null)
  const saving = ref(false)
  const successMessage = ref('')
  const errorMessage = ref('')
  
  // Inicializar formulario con datos actuales
  const initializeForm = () => {
    const currentSettings = window.$page?.props?.shopSettings || {}
    
    form.shop_name = currentSettings.shop_name || 'Daylen Cafetería'
    form.email = currentSettings.email || 'daylencoffee@gmail.com'
    form.phone = currentSettings.phone || '+595 986 195914'
    form.address = currentSettings.address || 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este'
    form.ruc = currentSettings.ruc || '12345678-9'
    form.about_us = currentSettings.about_us || 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad.'
    form.primary_color = currentSettings.primary_color || '#d97706'
    form.secondary_color = currentSettings.secondary_color || '#f59e0b'
    
    // Logo preview
    if (currentSettings.logo_url) {
      logoPreview.value = currentSettings.logo_url
    }
  }
  
  // Manejar upload de logo
  const handleLogoUpload = (event) => {
    const file = event.target.files[0]
    if (!file) return
  
    // Validar tipo de archivo
    const validTypes = ['image/jpeg', 'image/png', 'image/webp']
    if (!validTypes.includes(file.type)) {
      errorMessage.value = 'Formato de archivo no válido. Use JPG, PNG o WebP.'
      return
    }
  
    // Validar tamaño (5MB)
    if (file.size > 5 * 1024 * 1024) {
      errorMessage.value = 'El archivo es demasiado grande. Máximo 5MB.'
      return
    }
  
    logoFile.value = file
    form.logo = file
  
    // Crear preview
    const reader = new FileReader()
    reader.onload = (e) => {
      logoPreview.value = e.target.result
    }
    reader.readAsDataURL(file)
    
    errorMessage.value = ''
  }
  
  // Guardar configuración
  const saveSettings = async () => {
    saving.value = true
    successMessage.value = ''
    errorMessage.value = ''
  
    try {
      // Crear FormData para enviar archivos
      const formData = new FormData()
      
      // Agregar campos del formulario
      Object.keys(form).forEach(key => {
        if (key === 'logo' && form.logo) {
          formData.append('logo', form.logo)
        } else if (key !== 'logo') {
          formData.append(key, form[key])
        }
      })
  
      // Enviar request
      const response = await fetch('/admin/shop-settings', {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
  
      const result = await response.json()
  
      // En el método saveSettings, reemplazar la parte del éxito:
      if (result.success) {
        successMessage.value = result.message
  
        // ✅ FORZAR RECARGA COMPLETA DE LAS PROPS
        setTimeout(() => {
            // Recargar completamente la página para forzar el middleware
            window.location.reload();
        }, 1500);
      } else {
          errorMessage.value = result.message || 'Error al guardar la configuración'
      }
    } catch (error) {
      console.error('Error saving shop settings:', error)
      errorMessage.value = 'Error de conexión. Intente nuevamente.'
    } finally {
      saving.value = false
    }
  }
  
  // Restablecer formulario
  const resetForm = () => {
    initializeForm()
    logoFile.value = null
    if (logoInput.value) {
      logoInput.value.value = ''
    }
    successMessage.value = ''
    errorMessage.value = ''
  }
  
  // Inicializar al montar
  onMounted(() => {
    initializeForm()
  })
  </script>
  
  <style scoped>
  input[type="color"] {
    /*-webkit-appearance: none;*/
    border: none;
    padding: 0;
  }
  
  input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
  }
  
  input[type="color"]::-webkit-color-swatch {
    border: none;
    border-radius: 4px;
  }
  </style>