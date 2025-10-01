-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `daylen_db2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `daylen_db2`;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `emoji` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar categorías por defecto
INSERT INTO `categories` (`id`, `name`, `slug`, `emoji`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Café', 'cafe', 0, 1, NOW(), NOW()),
(2, 'Té', 'te', 1, 1, NOW(), NOW()),
(3, 'Postres', 'postres', 2, 1, NOW(), NOW()),
(4, 'Desayunos', 'desayunos', 3, 1, NOW(), NOW()),
(5, 'Ensaladas', 'ensaladas', 4, 1, NOW(), NOW()),
(6, 'Bebidas', 'bebidas', 10, 1, NOW(), NOW()),
(7, 'Snacks', 'snacks', 6, 1, NOW(), NOW()),
(8, 'Panadería', 'panaderia', 7, 1, NOW(), NOW());

-- Insertar usuario administrador por defecto (contraseña: password)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());

-- Insertar algunos productos de ejemplo
INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Espresso', 'Café concentrado y aromático', 3500.00, 50, 'espresso.jpg', 1, NOW(), NOW()),
(2, 'Cappuccino', 'Café espresso con leche vaporizada y espuma', 4500.00, 30, 'cappuccino.jpg', 1, NOW(), NOW()),
(3, 'Té Verde', 'Té verde japonés de alta calidad', 3000.00, 40, 'te-verde.jpg', 2, NOW(), NOW()),
(4, 'Tarta de Chocolate', 'Deliciosa tarta de chocolate negro', 7000.00, 15, 'tarta-chocolate.jpg', 3, NOW(), NOW());

-- Tabla de migraciones (necesaria para Laravel)
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar migraciones básicas
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_reset_tokens_table', 1),
('2019_08_19_000000_create_failed_jobs_table', 1),
('2019_12_14_000001_create_personal_access_tokens_table', 1),
('2023_01_01_000001_create_categories_table', 1),
('2023_01_01_000002_create_products_table', 1);

-- Reiniciar los autoincrementos
ALTER TABLE `users` AUTO_INCREMENT = 2;
ALTER TABLE `categories` AUTO_INCREMENT = 9;
ALTER TABLE `products` AUTO_INCREMENT = 5;
