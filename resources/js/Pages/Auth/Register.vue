<template>
    <GuestLayout>
        <Head title="Registrarse" />

        <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Crear Cuenta
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        O 
                        <a href="/login" class="font-medium text-amber-600 hover:text-amber-500">
                            iniciar sesión
                        </a>
                    </p>
                </div>

                <form class="mt-8 space-y-6" @submit.prevent="submit">
                    <!-- Mensajes de error específicos -->
                    <div v-if="Object.keys(validationErrors).length > 0" class="bg-red-50 border border-red-200 rounded-md p-4">
                        <ul class="text-red-600 text-sm space-y-1">
                            <li v-for="(error, field) in validationErrors" :key="field">{{ error }}</li>
                        </ul>
                    </div>

                    <div class="space-y-4">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                :class="[
                                    'relative block w-full rounded-md border-0 py-3 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 px-3',
                                    (nameError || form.errors.name) ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-amber-600'
                                ]"
                                placeholder="Ingresa tu nombre completo"
                            />
                            <p v-if="nameError" class="text-red-500 text-sm mt-1">{{ nameError }}</p>
                            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                :class="[
                                    'relative block w-full rounded-md border-0 py-3 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 px-3',
                                    (emailError || form.errors.email) ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-amber-600'
                                ]"
                                placeholder="tu@email.com"
                            />
                            <p v-if="emailError" class="text-red-500 text-sm mt-1">{{ emailError }}</p>
                            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                :class="[
                                    'relative block w-full rounded-md border-0 py-3 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 px-3',
                                    (passwordError || form.errors.password) ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-amber-600'
                                ]"
                                placeholder="Mínimo 8 caracteres"
                            />
                            <p v-if="passwordError" class="text-red-500 text-sm mt-1">{{ passwordError }}</p>
                            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                required
                                :class="[
                                    'relative block w-full rounded-md border-0 py-3 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 px-3',
                                    (passwordConfirmationError || form.errors.password_confirmation) ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-amber-600'
                                ]"
                                placeholder="Repite tu contraseña"
                            />
                            <p v-if="passwordConfirmationError" class="text-red-500 text-sm mt-1">{{ passwordConfirmationError }}</p>
                            <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing || !isFormValid"
                            class="group relative flex w-full justify-center rounded-md bg-amber-600 px-3 py-3 text-sm font-semibold text-white hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                        >
                            <span v-if="form.processing" class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            {{ form.processing ? 'Creando cuenta...' : 'Crear Cuenta' }}
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="/login" class="text-sm text-amber-600 hover:text-amber-500 font-medium">
                            ¿Ya tienes cuenta? Inicia sesión aquí
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref, computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Validaciones en tiempo real
const validationErrors = ref({});

const validateName = (name) => {
    if (name && name.length > 255) return 'El nombre no puede exceder los 255 caracteres';
    return null;
};

const validateEmail = (email) => {
    if (!email) return null;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) return 'Por favor ingresa un correo electrónico válido';
    return null;
};

const validatePassword = (password) => {
    if (!password) return null;
    if (password.length < 8) return 'La contraseña debe tener al menos 8 caracteres';
    return null;
};

const validatePasswordConfirmation = (password, confirmation) => {
    if (!confirmation) return null;
    if (password !== confirmation) return 'Las contraseñas no coinciden';
    return null;
};

// Computed para validaciones en tiempo real
const nameError = computed(() => validateName(form.name));
const emailError = computed(() => validateEmail(form.email));
const passwordError = computed(() => validatePassword(form.password));
const passwordConfirmationError = computed(() => 
    validatePasswordConfirmation(form.password, form.password_confirmation)
);

const isFormValid = computed(() => {
    return !nameError.value && !emailError.value && !passwordError.value && !passwordConfirmationError.value;
});

const submit = () => {
    // Validaciones antes del envío
    const errors = {};
    
    const nameErr = validateName(form.name);
    if (nameErr) errors.name = nameErr;
    
    const emailErr = validateEmail(form.email);
    if (emailErr) errors.email = emailErr;
    
    const passwordErr = validatePassword(form.password);
    if (passwordErr) errors.password = passwordErr;
    
    const passwordConfErr = validatePasswordConfirmation(form.password, form.password_confirmation);
    if (passwordConfErr) errors.password_confirmation = passwordConfErr;
    
    if (Object.keys(errors).length > 0) {
        validationErrors.value = errors;
        return;
    }
    
    validationErrors.value = {};
    
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>