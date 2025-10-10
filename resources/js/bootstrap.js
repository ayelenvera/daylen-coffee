import axios from 'axios';
window.axios = axios;

// Configuración básica de Axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// Obtener el token CSRF de la etiqueta meta
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token no encontrado');
}

// Interceptor para manejar errores de autenticación
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            // Redirigir al login si no está autenticado
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);