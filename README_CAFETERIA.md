# ☕ Sistema de Cafetería - Laravel + Inertia + Vue.js

Un sistema completo de e-commerce para una cafetería desarrollado con Laravel 10, Inertia.js y Vue.js 3.

## 🚀 Características Implementadas

### ✅ Fase 1 - Completada

- **Modelos y Migraciones**: Product, Order, OrderItem con relaciones completas
- **API RESTful**: Controladores completos con validación y manejo de errores
- **Sistema de Carrito**: Basado en sesiones de Laravel
- **Checkout Simulado**: Proceso de pago simulado sin integración real
- **Dashboard Admin**: Gestión completa de productos y pedidos
- **Frontend Reactivo**: Páginas Vue con TailwindCSS
- **Middleware de Seguridad**: Protección de rutas de administrador
- **Datos de Prueba**: Seeder con productos de cafetería

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 10
- **Frontend**: Vue.js 3 + Inertia.js
- **Styling**: TailwindCSS
- **Base de Datos**: MySQL/SQLite
- **Autenticación**: Laravel Breeze + Sanctum
- **API**: RESTful con validación completa

## 📋 Requisitos Previos

- PHP 8.1+
- Composer
- Node.js 16+
- MySQL 5.7+ o SQLite
- XAMPP (recomendado para desarrollo local)

## 🔧 Instalación y Configuración

### 1. Clonar y Configurar el Proyecto

```bash
# Navegar al directorio del proyecto
cd "c:\xampp\htdocs\coffee 2\daylen"

# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js
npm install
```

### 2. Configurar la Base de Datos

```bash
# Copiar archivo de configuración
copy .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

Editar el archivo `.env` con la configuración de tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffee_shop
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Ejecutar Migraciones y Seeders

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (crea productos y usuarios de prueba)
php artisan db:seed
```

### 4. Compilar Assets Frontend

```bash
# Compilar assets para desarrollo
npm run dev

# O para producción
npm run build
```

### 5. Iniciar el Servidor

```bash
# Iniciar servidor de desarrollo
php artisan serve
```

El proyecto estará disponible en: `http://localhost:8000`

## 👥 Usuarios de Prueba

El seeder crea automáticamente dos usuarios:

### Usuario Normal
- **Email**: user@test.com
- **Password**: password
- **Rol**: Cliente

### Usuario Administrador
- **Email**: admin@test.com
- **Password**: password
- **Rol**: Administrador

## 🎯 Funcionalidades del Sistema

### Para Clientes

1. **Página Principal** (`/home`)
   - Ver todos los productos disponibles
   - Agregar productos al carrito
   - Navegar a detalles de productos

2. **Detalle de Producto** (`/product/{id}`)
   - Ver información detallada del producto
   - Seleccionar cantidad
   - Agregar al carrito

3. **Carrito de Compras** (`/cart`)
   - Ver productos agregados
   - Modificar cantidades
   - Eliminar productos
   - Proceder al checkout

4. **Checkout** (`/checkout`)
   - Confirmación de pago simulado
   - Generación de número de pedido
   - Redirección post-compra

5. **Mis Pedidos** (`/orders`)
   - Ver historial de pedidos
   - Detalles de cada pedido
   - Estado de los pedidos

### Para Administradores

1. **Gestión de Productos** (`/admin/products`)
   - CRUD completo de productos
   - Control de stock
   - Gestión de precios

2. **Gestión de Pedidos** (`/admin/orders`)
   - Ver todos los pedidos
   - Cambiar estado de pedidos
   - Ver detalles de clientes

## 🔌 API Endpoints

### Públicos
- `GET /api/products` - Listar productos
- `GET /api/products/{id}` - Ver producto específico
- `POST /api/cart/add` - Agregar al carrito
- `POST /api/cart/remove` - Quitar del carrito
- `GET /api/cart` - Ver carrito
- `DELETE /api/cart/clear` - Limpiar carrito

### Protegidos (Requieren autenticación)
- `GET /api/orders` - Pedidos del usuario
- `GET /api/orders/{id}` - Detalle de pedido
- `POST /api/checkout` - Procesar checkout

### Administrador
- `POST /api/admin/products` - Crear producto
- `PUT /api/admin/products/{id}` - Actualizar producto
- `DELETE /api/admin/products/{id}` - Eliminar producto
- `GET /api/admin/orders` - Todos los pedidos
- `PATCH /api/admin/orders/{id}/status` - Cambiar estado

## 🗄️ Estructura de la Base de Datos

### Tabla `products`
- `id` - Primary key
- `name` - Nombre del producto
- `description` - Descripción
- `price` - Precio (decimal 8,2)
- `stock` - Cantidad en stock
- `image` - URL de imagen (nullable)
- `created_at`, `updated_at` - Timestamps

### Tabla `orders`
- `id` - Primary key
- `user_id` - Foreign key a users
- `total` - Total del pedido (decimal 8,2)
- `status` - Estado (pending, paid, cancelled)
- `payment_id` - ID de pago (nullable)
- `created_at`, `updated_at` - Timestamps

### Tabla `order_items`
- `id` - Primary key
- `order_id` - Foreign key a orders
- `product_id` - Foreign key a products
- `quantity` - Cantidad
- `subtotal` - Subtotal (decimal 8,2)
- `created_at`, `updated_at` - Timestamps

### Tabla `users` (modificada)
- Campos estándar de Laravel Breeze
- `is_admin` - Boolean para roles de administrador

## 🎨 Personalización

### Agregar Nuevos Productos
1. Acceder como administrador
2. Ir a `/admin/products`
3. Hacer clic en "Nuevo Producto"
4. Completar formulario y guardar

### Modificar Estilos
Los estilos están en `resources/css/app.css` y usan TailwindCSS. Puedes personalizar:
- Colores en `tailwind.config.js`
- Estilos globales en `app.css`
- Componentes específicos en las páginas Vue

### Agregar Nuevas Funcionalidades
1. Crear migraciones para nuevas tablas
2. Crear modelos con relaciones
3. Crear controladores API
4. Agregar rutas en `routes/api.php`
5. Crear páginas Vue en `resources/js/Pages/`

## 🐛 Solución de Problemas

### Error de Base de Datos
```bash
# Verificar conexión
php artisan migrate:status

# Recrear base de datos
php artisan migrate:fresh --seed
```

### Error de Assets Frontend
```bash
# Limpiar cache y recompilar
npm run dev
# o
npm run build
```

### Error de Permisos
```bash
# Verificar permisos de storage
php artisan storage:link
```

## 📝 Notas Importantes

1. **Pago Simulado**: El sistema no integra con procesadores de pago reales. Es solo para demostración.

2. **Sesiones**: El carrito usa sesiones de Laravel, por lo que se mantiene durante la sesión del usuario.

3. **Seguridad**: Las rutas de administrador están protegidas con middleware personalizado.

4. **Responsive**: El diseño es completamente responsive usando TailwindCSS.

5. **Validación**: Todas las entradas están validadas tanto en frontend como backend.

## 🚀 Próximos Pasos (Fase 2)

- Integración con MercadoPago real
- Sistema de notificaciones por email
- Panel de estadísticas y reportes
- Sistema de cupones y descuentos
- Gestión de inventario avanzada
- API para aplicación móvil

## 📞 Soporte

Para cualquier duda o problema, revisar:
1. Logs de Laravel en `storage/logs/`
2. Consola del navegador para errores de JavaScript
3. Documentación oficial de Laravel, Vue.js e Inertia.js

---

**¡Disfruta tu nueva cafetería digital! ☕✨**
