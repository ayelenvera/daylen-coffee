// resources/js/middleware/auth.js
import { router } from '@inertiajs/vue3'

export function handleAuthError(error) {
    console.log('Auth error handled:', error)
    
    if (error.response?.status === 401 && error.response?.data?.redirect) {
        // Redirigir al login si no está autenticado
        router.visit(error.response.data.redirect)
        return true
    }
    
    if (error.response?.status === 403) {
        // Mostrar error de permisos
        alert('No tiene permisos para realizar esta acción')
        return true
    }
    
    // Si es un error de red o otro tipo, mostrar mensaje genérico
    if (error.message) {
        alert(error.message)
        return true
    }
    
    return false
}

export function redirectIfUnauthenticated(error) {
    return handleAuthError(error)
}

export default {
    handleAuthError,
    redirectIfUnauthenticated
}