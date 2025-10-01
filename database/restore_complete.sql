-- Script de restauración completo para el sistema de cafetería
-- Este script debe ejecutarse en phpMyAdmin o en la línea de comandos de MySQL

-- 1. Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `daylen_db2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `daylen_db2`;

-- 2. Eliminar tablas si existen (en el orden correcto para evitar problemas de claves foráneas)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `product_addons`;
DROP TABLE IF EXISTS `product_sizes`;
DROP TABLE IF EXISTS `product_toppings`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `personal_access_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
SET FOREIGN_KEY_CHECKS = 1;

-- 3. Crear tablas
-- Tabla de usuarios
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de categorías
CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emoji` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de productos
CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `has_sizes` tinyint(1) NOT NULL DEFAULT '0',
  `has_toppings` tinyint(1) NOT NULL DEFAULT '0',
  `has_addons` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de pedidos
CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de ítems de pedido
CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `toppings` text COLLATE utf8mb4_unicode_ci,
  `addons` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de tamaños de productos
CREATE TABLE `product_sizes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_sizes_product_id_foreign` (`product_id`),
  CONSTRAINT `product_sizes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de ingredientes adicionales
CREATE TABLE `product_toppings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_toppings_product_id_foreign` (`product_id`),
  CONSTRAINT `product_toppings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de adiciones
CREATE TABLE `product_addons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_addons_product_id_foreign` (`product_id`),
  CONSTRAINT `product_addons_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Insertar datos iniciales
-- Insertar usuario administrador
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_admin`, `created_at`, `updated_at`) VALUES
('Administrador', 'admin@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, NOW(), NOW());

-- Insertar categorías
INSERT INTO `categories` (`name`, `slug`, `emoji`, `is_active`, `created_at`, `updated_at`) VALUES
('Café', 'cafe', 0, 1, NOW(), NOW()),
('Té', 'te', 1, 1, NOW(), NOW()),
('Postres', 'postres', 2, 1, NOW(), NOW()),
('Desayunos', 'desayunos', 3, 1, NOW(), NOW()),
('Ensaladas', 'ensaladas', 4, 1, NOW(), NOW()),
('Bebidas', 'bebidas', 10, 1, NOW(), NOW()),
('Snacks', 'snacks', 6, 1, NOW(), NOW()),
('Panadería', 'panaderia', 7, 1, NOW(), NOW());

-- Insertar productos de ejemplo
INSERT INTO `products` (`name`, `description`, `price`, `stock`, `image`, `category_id`, `has_sizes`, `has_toppings`, `has_addons`, `is_active`, `created_at`, `updated_at`) VALUES
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

-- Insertar tamaños para productos (ejemplo para Cappuccino)
INSERT INTO `product_sizes` (`product_id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(2, 'Pequeño (8oz)', 5500.00, NOW(), NOW()),
(2, 'Mediano (12oz)', 6500.00, NOW(), NOW()),
(2, 'Grande (16oz)', 7500.00, NOW(), NOW()),
(3, 'Pequeño (8oz)', 6000.00, NOW(), NOW()),
(3, 'Mediano (12oz)', 7000.00, NOW(), NOW()),
(3, 'Grande (16oz)', 8000.00, NOW(), NOW());

-- Insertar ingredientes adicionales (ejemplo para Cappuccino)
INSERT INTO `product_toppings` (`product_id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(2, 'Extra shot de espresso', 2000.00, NOW(), NOW()),
(2, 'Sirope de vainilla', 1000.00, NOW(), NOW()),
(2, 'Sirope de caramelo', 1000.00, NOW(), NOW()),
(2, 'Canela en polvo', 500.00, NOW(), NOW()),
(2, 'Chocolate en polvo', 500.00, NOW(), NOW());

-- Insertar adiciones (disponibles para varios productos)
INSERT INTO `product_addons` (`product_id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Leche de almendras', 1500.00, NOW(), NOW()),
(1, 'Leche de avena', 1500.00, NOW(), NOW()),
(1, 'Leche de soya', 1500.00, NOW(), NOW()),
(1, 'Crema batida', 1000.00, NOW(), NOW()),
(1, 'Chocolate rallado', 500.00, NOW(), NOW());

-- 5. Mensaje de éxito
SELECT '✅ ¡Base de datos restaurada con éxito!' AS mensaje;
