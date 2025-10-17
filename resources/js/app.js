import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'Daylen Cafetería';

// ✅ CONFIGURACIÓN OBLIGATORIA PARA RAILWAY
import axios from 'axios';

// Forzar base URL HTTPS
axios.defaults.baseURL = 'https://daylencoffee.up.railway.app';
axios.defaults.withCredentials = true;

// Interceptor para manejar redirects
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 302 && error.response.headers.location) {
      // Manejar redirects del servidor
      window.location.href = error.response.headers.location;
      return Promise.reject(error);
    }
    return Promise.reject(error);
  }
);

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')
    ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#f59e0b',
    },
});