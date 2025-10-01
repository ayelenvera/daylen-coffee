-- Script para completar la base de datos daylen_db2
-- Este script agregará las tablas faltantes y datos de ejemplo

USE `daylen_db2`;

-- 1. Crear tablas faltantes si no existen

-- Tabla de sesiones (si no existe)
CREATE TABLE IF NOT EXISTS `sessions` (
    `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_id` bigint UNSIGNED DEFAULT NULL,
    `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `user_agent` text COLLATE utf8mb4_unicode_ci,
    `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `last_activity` int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de trabajos fallidos (si no existe)
CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de caché (si no existe)
CREATE TABLE IF NOT EXISTS `cache` (
    `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `expiration` int NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de bloqueos de caché (si no existe)
CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `expiration` int NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Verificar y agregar columnas faltantes a tablas existentes

-- Asegurar que la tabla users tenga la columna is_admin
SET @dbname = DATABASE();
SET @tablename = 'users';
SET @columnname = 'is_admin';
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE (TABLE_SCHEMA = @dbname
        AND TABLE_NAME = @tablename
        AND COLUMN_NAME = @columnname)) = 0,
    CONCAT('ALTER TABLE `', @tablename, '` ADD `', @columnname, '` TINYINT(1) NOT NULL DEFAULT 0 AFTER `remember_token`'),
    'SELECT 1'
));

PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 3. Insertar datos de ejemplo solo en tablas vacías

-- Insertar usuario administrador si no existe
INSERT IGNORE INTO `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_admin`, `created_at`, `updated_at`)
VALUES ('Administrador', 'admin@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, NOW(), NOW());

-- Insertar categorías si no existen
INSERT IGNORE INTO `categories` (`name`, `slug`, `emoji`, `is_active`, `created_at`, `updated_at`) VALUES
('Café', 'cafe', 0, 1, NOW(), NOW()),
('Té', 'te', 1, 1, NOW(), NOW()),
('Postres', 'postres', 2, 1, NOW(), NOW()),
('Desayunos', 'desayunos', 3, 1, NOW(), NOW()),
('Ensaladas', 'ensaladas', 4, 1, NOW(), NOW()),
('Bebidas', 'bebidas', 10, 1, NOW(), NOW()),
('Snacks', 'snacks', 6, 1, NOW(), NOW()),
('Panadería', 'panaderia', 7, 1, NOW(), NOW());

-- Insertar productos de ejemplo si no existen
INSERT IGNORE INTO `products` (`name`, `description`, `price`, `stock`, `image`, `category_id`, `has_sizes`, `has_toppings`, `has_addons`, `is_active`, `created_at`, `updated_at`) VALUES
('Espresso', 'Café concentrado y aromático, la esencia del café puro.', 4000.00, 100, 'espresso.jpg', 1, 0, 0, 1, 1, NOW(), NOW()),
('Cappuccino', 'Café espresso con leche vaporizada y una capa de espuma de leche cremosa.', 5500.00, 80, 'cappuccino.jpg', 1, 1, 1, 1, 1, NOW(), NOW()),
('Latte', 'Café espresso con más leche vaporizada y una ligera capa de espuma.', 6000.00, 75, 'latte.jpg', 1, 1, 1, 1, 1, NOW(), NOW()),
('Mocha', 'Deliciosa combinación de espresso, leche vaporizada, chocolate y crema batida.', 6500.00, 60, 'mocha.jpg', 1, 1, 1, 1, 1, NOW(), NOW()),
('Americano', 'Café espresso diluido en agua caliente, más suave que un espresso.', 4500.00, 90, 'americano.jpg', 1, 1, 0, 1, 1, NOW(), NOW()),
('Macchiato', 'Espresso "manchado" con una pequeña cantidad de leche vaporizada.', 5000.00, 70, 'macchiato.jpg', 1, 0, 0, 1, 1, NOW(), NOW()),
('Flat White', 'Café espresso con leche vaporizada y una capa fina de microespuma.', 6000.00, 65, 'flat-white.jpg', 1, 1, 0, 1, 1, NOW(), NOW()),
('Cortado', 'Espresso cortado con una pequeña cantidad de leche caliente.', 4500.00, 70, 'cortado.jpg', 1, 0, 0, 1, 1, NOW(), NOW()),
('Iced Coffee', 'Café frío servido con hielo y leche, refrescante y delicioso.', 5500.00, 50, 'iced-coffee.jpg', 1, 1, 1, 1, 1, NOW(), NOW()),
('Frappé', 'Bebida helada de café batido con hielo y crema batida.', 6500.00, 45, 'frappe.jpg', 1, 1, 1, 1, 1, NOW(), NOW());

-- 4. Insertar tamaños, ingredientes y adiciones para productos existentes
-- (Estos solo se insertarán si no existen ya)

-- Tamaños para Cappuccino
INSERT IGNORE INTO `product_sizes` (`product_id`, `name`, `price`, `created_at`, `updated_at`)
SELECT 2, 'Pequeño (8oz)', 5500.00, NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `product_sizes` WHERE `product_id` = 2 AND `name` = 'Pequeño (8oz)');

INSERT IGNORE INTO `product_sizes` (`product_id`, `name`, `price`, `created_at`, `updated_at`)
SELECT 2, 'Mediano (12oz)', 6500.00, NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `product_sizes` WHERE `product_id` = 2 AND `name` = 'Mediano (12oz)');

-- Ingredientes adicionales para Cappuccino
INSERT IGNORE INTO `product_toppings` (`product_id`, `name`, `price`, `created_at`, `updated_at`)
SELECT 2, 'Extra shot de espresso', 2000.00, NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `product_toppings` WHERE `product_id` = 2 AND `name` = 'Extra shot de espresso');

-- Adiciones para productos
INSERT IGNORE INTO `product_addons` (`product_id`, `name`, `price`, `created_at`, `updated_at`)
SELECT 1, 'Leche de almendras', 1500.00, NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `product_addons` WHERE `product_id` = 1 AND `name` = 'Leche de almendras');

-- 5. Mensaje de éxito
SELECT '✅ ¡Base de datos completada con éxito!' AS mensaje;
