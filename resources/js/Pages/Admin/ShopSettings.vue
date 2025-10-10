<!-- resources/js/Pages/Admin/ShopSettings.vue -->
<template>
  <Head title="Configuración" />
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
            :disabled="saving || !isFormValid"
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
                      <span class="text-xs text-gray-500 ml-1">({{ form.shop_name.length }}/50 caracteres)</span>
                    </label>
                    <input 
                      v-model="form.shop_name"
                      @input="validateShopName"
                      type="text"
                      maxlength="50"
                      class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                      :class="{'border-red-500': errors.shop_name}"
                      required
                      placeholder="Ej: Daylen Cafetería"
                    />
                    <p v-if="errors.shop_name" class="text-red-500 text-xs mt-1">{{ errors.shop_name }}</p>
                  </div>

                  <!-- RUC -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      RUC *
                      <span class="text-xs text-gray-500 ml-1">({{ form.ruc.length }}/20 caracteres)</span>
                    </label>
                    <input 
                      v-model="form.ruc"
                      @input="validateRuc"
                      type="text"
                      maxlength="20"
                      class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                      :class="{'border-red-500': errors.ruc}"
                      required
                      placeholder="Ej: 12345678-9"
                    />
                    <p v-if="errors.ruc" class="text-red-500 text-xs mt-1">{{ errors.ruc }}</p>
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
                      Nuevo Logo
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
                      <p class="font-medium text-sm text-gray-700 mb-2">Logo actual:</p>
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
                      <span class="text-xs text-gray-500 ml-1">Formato: +595 XXX XXXXXX</span>
                    </label>
                    <input 
                      v-model="form.phone"
                      @input="formatPhone"
                      @keydown="preventInvalidPhoneInput"
                      type="text"
                      maxlength="15"
                      class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 font-mono"
                      :class="{'border-red-500': errors.phone}"
                      required
                      placeholder="+595 986 195914"
                    />
                    <p v-if="errors.phone" class="text-red-500 text-xs mt-1">{{ errors.phone }}</p>
                  </div>

                  <!-- Email -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Email *
                      <span class="text-xs text-gray-500 ml-1">({{ form.email.length }}/100 caracteres)</span>
                    </label>
                    <input 
                      v-model="form.email"
                      @input="validateEmail"
                      type="email"
                      maxlength="100"
                      class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                      :class="{'border-red-500': errors.email}"
                      required
                      placeholder="Ej: daylencoffee@gmail.com"
                    />
                    <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                  </div>
                </div>

                <!-- Dirección -->
                <div class="mt-6">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Dirección Completa *
                    <span class="text-xs text-gray-500 ml-1">({{ form.address.length }}/200 caracteres)</span>
                  </label>
                  <textarea 
                    v-model="form.address"
                    @input="validateAddress"
                    rows="3"
                    maxlength="200"
                    class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                    :class="{'border-red-500': errors.address}"
                    required
                    placeholder="Ej: Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este"
                  ></textarea>
                  <p v-if="errors.address" class="text-red-500 text-xs mt-1">{{ errors.address }}</p>
                </div>
              </div>

              <!-- Sección: Acerca de Nosotros -->
              <div class="border-b border-gray-200 pb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">📝 Acerca de Nosotros</h3> 
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción de la Tienda *
                    <span class="text-xs text-gray-500 ml-1">({{ form.about_us.length }}/500 caracteres)</span>
                  </label>
                  <textarea 
                    v-model="form.about_us"
                    @input="validateAboutUs"
                    rows="4"
                    maxlength="500"
                    class="w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500"
                    :class="{'border-red-500': errors.about_us}"
                    required
                    placeholder="Describe tu cafetería, misión, valores, etc..."
                  ></textarea>
                  <p v-if="errors.about_us" class="text-red-500 text-xs mt-1">{{ errors.about_us }}</p>
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
                        @input="validateColor('primary_color')"
                        type="text"
                        class="flex-1 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 font-mono text-sm"
                        :class="{'border-red-500': errors.primary_color}"
                        placeholder="#d97706"
                        maxlength="7"
                      />
                    </div>
                    <p v-if="errors.primary_color" class="text-red-500 text-xs mt-1">{{ errors.primary_color }}</p>
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
                        @input="validateColor('secondary_color')"
                        type="text"
                        class="flex-1 border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 font-mono text-sm"
                        :class="{'border-red-500': errors.secondary_color}"
                        placeholder="#f59e0b"
                        maxlength="7"
                      />
                    </div>
                    <p v-if="errors.secondary_color" class="text-red-500 text-xs mt-1">{{ errors.secondary_color }}</p>
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
                  :disabled="saving || !isFormValid"
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
              
              <div class="mt-4 text-sm gap-4 text-gray-600">
                <strong>Dirección: </strong> 
                <span class="gap-4 text-sm text-gray-600">{{ form.address || 'Dirección de la tienda...' }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
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
const errors = reactive({
  shop_name: '',
  ruc: '',
  phone: '',
  email: '',
  address: '',
  about_us: '',
  primary_color: '',
  secondary_color: ''
})

// Computed para validar formulario completo
const isFormValid = computed(() => {
  return form.shop_name && 
         form.ruc && 
         form.phone && 
         form.email && 
         form.address && 
         form.about_us &&
         !errors.shop_name &&
         !errors.ruc &&
         !errors.phone &&
         !errors.email &&
         !errors.address &&
         !errors.about_us &&
         !errors.primary_color &&
         !errors.secondary_color
})

// Formatear teléfono automáticamente - VERSIÓN CORREGIDA
const formatPhone = (event) => {
    let input = event.target.value
    
    // Remover todo excepto números y el signo +
    let cleaned = input.replace(/[^\d+]/g, '')
    
    // Asegurar que empiece con +595
    if (!cleaned.startsWith('+595')) {
        cleaned = '+595' + cleaned.replace(/^\+?595?/, '')
    }
    
    // Limitar a 13 caracteres (+595 + 9 dígitos)
    cleaned = cleaned.substring(0, 13)
    
    // Formatear con espacios CORREGIDO: 3 + 6 dígitos
    if (cleaned.length > 4) {
        const prefix = cleaned.substring(0, 4) // +595
        const numbers = cleaned.substring(4).replace(/\D/g, '')
        
        let formatted = prefix
        
        if (numbers.length > 0) {
            formatted += ' ' + numbers.substring(0, 3)  // Primeros 3 dígitos
        }
        if (numbers.length > 3) {
            formatted += ' ' + numbers.substring(3, 9)  // Últimos 6 dígitos (sin dividir)
        }
        
        form.phone = formatted
    } else {
        form.phone = cleaned
    }
    
    validatePhone()
}

// Prevenir entrada inválida en teléfono
const preventInvalidPhoneInput = (event) => {
  // Permitir teclas de control
  if (event.ctrlKey || event.altKey || event.metaKey) return
  
  // Permitir teclas de navegación
  if ([8, 9, 13, 27, 37, 38, 39, 40, 46].includes(event.keyCode)) return
  
  const char = event.key
  
  // Solo permitir números y + (solo al inicio)
  if (!/[\d+]/.test(char)) {
    event.preventDefault()
    return
  }
  
  // Si ya hay +595, no permitir otro +
  if (char === '+' && form.phone.includes('+')) {
    event.preventDefault()
    return
  }
}

// Validaciones
const validateShopName = () => {
  if (form.shop_name.length > 50) {
    errors.shop_name = 'El nombre no puede exceder 50 caracteres'
  } else if (form.shop_name.length < 2) {
    errors.shop_name = 'El nombre debe tener al menos 2 caracteres'
  } else {
    errors.shop_name = ''
  }
}

const validateRuc = () => {
  const rucRegex = /^[0-9]{6,10}-[0-9]{1}$/
  if (form.ruc.length > 20) {
    errors.ruc = 'El RUC no puede exceder 20 caracteres'
  } else if (!rucRegex.test(form.ruc)) {
    errors.ruc = 'Formato de RUC inválido. Use: 12345678-9'
  } else {
    errors.ruc = ''
  }
}

// Validar teléfono - VERSIÓN CORREGIDA
const validatePhone = () => {
    // Validar formato: +595 XXX XXXXXX (3 + 6 dígitos)
    const phoneRegex = /^\+595\s\d{3}\s\d{6}$/
    
    if (!phoneRegex.test(form.phone)) {
        errors.phone = 'Formato inválido. Use: +595 XXX XXXXXX'
    } else {
        errors.phone = ''
    }
}

const validateEmail = () => {
  const emailRegex = /^[^\s@]+@gmail\.com$/i
  if (form.email.length > 100) {
    errors.email = 'El email no puede exceder 100 caracteres'
  } else if (!emailRegex.test(form.email)) {
    errors.email = 'Debe ser una dirección de Gmail válida (ejemplo@gmail.com)'
  } else {
    errors.email = ''
  }
}

const validateAddress = () => {
  if (form.address.length > 200) {
    errors.address = 'La dirección no puede exceder 200 caracteres'
  } else if (form.address.length < 10) {
    errors.address = 'La dirección debe tener al menos 10 caracteres'
  } else {
    errors.address = ''
  }
}

const validateAboutUs = () => {
  if (form.about_us.length > 500) {
    errors.about_us = 'La descripción no puede exceder 500 caracteres'
  } else if (form.about_us.length < 10) {
    errors.about_us = 'La descripción debe tener al menos 10 caracteres'
  } else {
    errors.about_us = ''
  }
}

const validateColor = (colorField) => {
  const colorRegex = /^#[0-9A-F]{6}$/i
  if (!colorRegex.test(form[colorField])) {
    errors[colorField] = 'Formato de color inválido. Use: #RRGGBB'
  } else {
    errors[colorField] = ''
  }
}

// Inicializar formulario con datos actuales
const initializeForm = () => {
  const currentSettings = window.$page?.props?.shopSettings || {}
  
  form.shop_name = currentSettings.shop_name || 'Daylen Cafetería'
  form.email = currentSettings.email || 'daylencoffee@gmail.com'
  
  // Formatear teléfono automáticamente al inicializar
  const currentPhone = currentSettings.phone || '+595 986 195914'
  form.phone = currentPhone
  
  form.address = currentSettings.address || 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este'
  form.ruc = currentSettings.ruc || '12345678-9'
  form.about_us = currentSettings.about_us || 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad.'
  form.primary_color = currentSettings.primary_color || '#d97706'
  form.secondary_color = currentSettings.secondary_color || '#f59e0b'
  
  // Ejecutar validaciones iniciales
  validateShopName()
  validateRuc()
  validatePhone()
  validateEmail()
  validateAddress()
  validateAboutUs()
  validateColor('primary_color')
  validateColor('secondary_color')
  
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
  // Validar todo antes de enviar
  validateShopName()
  validateRuc()
  validatePhone()
  validateEmail()
  validateAddress()
  validateAboutUs()
  validateColor('primary_color')
  validateColor('secondary_color')
  
  if (!isFormValid.value) {
    errorMessage.value = 'Por favor, corrige los errores en el formulario'
    return
  }
  
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