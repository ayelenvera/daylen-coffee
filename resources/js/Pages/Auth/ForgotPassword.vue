<template>
    <GuestLayout>
        <Head title="Recuperar Contraseña" />

        <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Recuperar Contraseña
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        ¿Olvidaste tu contraseña? Ingresa tu correo electrónico y te enviaremos un enlace para restablecerla.
                    </p>
                </div>

                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-md p-4"
                >
                    {{ status }}
                </div>

                <form class="mt-8 space-y-6" @submit.prevent="submit">
                    <div v-if="form.errors.email" class="bg-red-50 border border-red-200 rounded-md p-4">
                        <p class="text-red-600 text-sm">{{ form.errors.email }}</p>
                    </div>

                    <div class="rounded-md shadow-sm">
                        <div>
                            <label for="email" class="sr-only">Correo Electrónico</label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="relative block w-full rounded-md border-0 py-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-amber-600 sm:text-sm sm:leading-6 px-3"
                                placeholder="Correo Electrónico"
                            />
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="group relative flex w-full justify-center rounded-md bg-amber-600 px-3 py-3 text-sm font-semibold text-white hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 disabled:opacity-50 transition-colors duration-200"
                        >
                            <span v-if="form.processing" class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            {{ form.processing ? 'Enviando...' : 'Enviar Enlace de Recuperación' }}
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="/login" class="text-sm text-amber-600 hover:text-amber-500 font-medium">
                            ← Volver al inicio de sesión
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

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('/forgot-password');
};
</script>