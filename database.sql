-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 09-10-2025 a las 19:53:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `daylen_db3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('daylencafeteria-cache-424f74a6a7ed4d4ed4761507ebcd209a6ef0937b', 'i:1;', 1759553663),
('daylencafeteria-cache-424f74a6a7ed4d4ed4761507ebcd209a6ef0937b:timer', 'i:1759553663;', 1759553663);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','completed','abandoned') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `options` longtext DEFAULT NULL CHECK (json_valid(`options`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `emoji` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `emoji`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cafés', 'cafes', 0, 1, '2025-09-28 21:02:07', '2025-09-29 01:14:39'),
(2, 'Tés', 'tes', 1, 1, '2025-09-28 21:02:07', '2025-09-29 01:14:48'),
(3, 'Postres', 'postres', 2, 1, '2025-09-28 21:02:07', '2025-09-28 21:02:07'),
(4, 'Desayunos', 'desayunos', 3, 1, '2025-09-28 21:02:07', '2025-09-28 21:02:07'),
(5, 'Ensaladas', 'ensaladas', 4, 1, '2025-09-28 21:02:07', '2025-09-28 21:02:07'),
(6, 'Bebidas', 'bebidas', 5, 1, '2025-09-28 21:02:07', '2025-10-03 15:32:48'),
(7, 'Snacks', 'snacks', 24, 1, '2025-09-28 21:02:07', '2025-09-28 22:14:27'),
(8, 'Panadería', 'panaderia', 7, 1, '2025-09-28 21:02:07', '2025-09-28 21:02:07'),
(9, 'Otros', 'otros', 28, 1, '2025-09-28 21:27:55', '2025-09-28 21:27:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_04_222739_create_personal_access_tokens_table', 1),
(5, '2025_09_04_223104_create_products_table', 1),
(6, '2025_09_04_223117_create_orders_table', 1),
(7, '2025_09_04_223124_create_order_items_table', 1),
(8, '2025_09_09_162916_add_is_admin_to_users_table', 1),
(9, '2025_09_21_171604_add_customer_fields_to_orders_table', 1),
(10, '2025_09_21_171621_add_options_to_order_items_table', 1),
(11, '2025_09_21_213426_add_customization_fields_to_products_table', 1),
(12, '2025_09_21_213449_create_product_sizes_table', 1),
(13, '2025_09_21_213457_create_product_toppings_table', 1),
(14, '2025_09_21_213502_create_product_addons_table', 1),
(15, '2025_09_28_175255_create_carts_table', 2),
(16, '2025_09_28_175335_create_cart_items_table', 3),
(17, '2025_09_28_151600_create_categories_table', 4),
(18, '2025_09_29_061654_create_product_customization_rules_table', 5),
(19, '2025_09_29_082228_add_missing_fields_to_customization_rules', 6),
(20, '2025_09_29_101856_add_toppings_addons_to_order_items_table', 7),
(21, '2025_09_29_112212_add_payment_fields_to_orders_table', 8),
(22, '2025_09_29_221128_create_shop_settings_table', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `card_issuer` varchar(255) DEFAULT NULL,
  `card_last_four` varchar(255) DEFAULT NULL,
  `card_expiration` varchar(255) DEFAULT NULL,
  `payment_response` text DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `payment_status`, `card_issuer`, `card_last_four`, `card_expiration`, `payment_response`, `payment_id`, `created_at`, `updated_at`, `customer_name`, `phone`, `address`, `payment_method`, `notes`) VALUES
(1, 2, 12000.00, 'paid', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-09-29 00:43:25', '2025-09-29 08:54:46', 'User', '+595 975 430721', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'efectivo', NULL),
(2, 2, 21000.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-09-29 11:36:06', '2025-09-29 11:36:06', 'User', '+595 975 430721', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'efectivo', NULL),
(6, 2, 34000.00, 'paid', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-09-29 13:21:54', '2025-09-29 16:42:43', 'User', '+595 975 430 721', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'efectivo', NULL),
(7, 2, 20000.00, 'cancelled', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-09-29 13:51:08', '2025-09-29 16:42:29', 'User', '+595 975 430 721', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'efectivo', NULL),
(8, 2, 5000.00, 'paid', 'paid', 'UENO BANK', '5555', '55/55', '{\"status\":\"success\",\"message\":\"Pago procesado exitosamente\",\"transaction_id\":\"TXN_1759165576_8\",\"simulated\":true}', NULL, '2025-09-29 16:06:16', '2025-09-29 16:06:16', 'User', '+595 975 430 721', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'tarjeta', NULL),
(9, 2, 6400.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:51:52', '2025-09-29 17:51:52', 'User', '+595 975 430 721', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'efectivo', NULL),
(10, 3, 107600.00, 'paid', 'paid', 'BANCO FAMILIAR', '0505', '05/05', '{\"status\":\"success\",\"message\":\"Pago procesado exitosamente\",\"transaction_id\":\"TXN_1759193518_10\",\"simulated\":true}', NULL, '2025-09-29 23:51:58', '2025-09-29 23:51:58', 'Aye', '+595 974 811 055', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'tarjeta', NULL),
(14, 3, 55000.00, 'paid', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-10-04 03:15:20', '2025-10-04 03:15:20', 'Aye', '+595 974 811 055', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'tarjeta', NULL),
(15, 3, 37200.00, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, '2025-10-04 03:15:20', '2025-10-04 03:15:20', 'Aye', '+595 975 430 721', 'Remansito, Ciudad del Este, Alto Paraná, Región Oriental, 100151, Paraguay', 'efectivo', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `sugar` varchar(255) DEFAULT NULL,
  `toppings` longtext DEFAULT NULL CHECK (json_valid(`toppings`)),
  `addons` longtext DEFAULT NULL CHECK (json_valid(`addons`))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `subtotal`, `created_at`, `updated_at`, `price`, `size`, `sugar`, `toppings`, `addons`) VALUES
(1, 1, 40, 1, 5000.00, '2025-09-29 00:43:25', '2025-09-29 00:43:25', 5000.00, 'Mediano', 'Normal', NULL, NULL),
(2, 1, 38, 1, 7000.00, '2025-09-29 00:43:25', '2025-09-29 00:43:25', 7000.00, 'Mediano', 'Normal', NULL, NULL),
(3, 2, 3, 1, 9000.00, '2025-09-29 11:36:06', '2025-09-29 11:36:06', 9000.00, '350ml', 'Poca', NULL, NULL),
(4, 2, 38, 1, 7000.00, '2025-09-29 11:36:06', '2025-09-29 11:36:06', 7000.00, 'Mediano', 'Normal', NULL, NULL),
(5, 2, 40, 1, 5000.00, '2025-09-29 11:36:06', '2025-09-29 11:36:06', 5000.00, 'Mediano', 'Normal', NULL, NULL),
(6, 6, 3, 2, 18000.00, '2025-09-29 13:21:54', '2025-09-29 13:21:54', 9000.00, '250ml', 'Poca', '[\"Caramelo\"]', '[\"Canela\"]'),
(7, 6, 8, 1, 10000.00, '2025-09-29 13:21:54', '2025-09-29 13:21:54', 10000.00, '350ml', 'Normal', '[\"Vainilla\"]', '[\"Leche extra\"]'),
(8, 6, 36, 1, 6000.00, '2025-09-29 13:21:54', '2025-09-29 13:21:54', 6000.00, 'Mediano', 'Normal', '[]', '[]'),
(9, 7, 3, 1, 13000.00, '2025-09-29 13:51:08', '2025-09-29 13:51:08', 13000.00, '250ml', 'Normal', '[\"Caramelo\"]', '[\"Canela\"]'),
(10, 7, 38, 1, 7000.00, '2025-09-29 13:51:08', '2025-09-29 13:51:08', 7000.00, 'Mediano', 'Normal', '[]', '[]'),
(11, 8, 40, 1, 5000.00, '2025-09-29 16:06:16', '2025-09-29 16:06:16', 5000.00, 'Mediano', 'Normal', '[]', '[]'),
(12, 9, 1, 1, 6400.00, '2025-09-29 17:51:52', '2025-09-29 17:51:52', 6400.00, 'Mediano', 'Normal', '[]', '[]'),
(13, 10, 38, 3, 27000.00, '2025-09-29 23:51:58', '2025-09-29 23:51:58', 9000.00, 'Mediano', 'Normal', '[]', '[]'),
(14, 10, 3, 1, 17000.00, '2025-09-29 23:51:58', '2025-09-29 23:51:58', 17000.00, '500ml', 'Poca', '[\"Vainilla\"]', '[\"Crema batida\"]'),
(15, 10, 44, 1, 26000.00, '2025-09-29 23:51:58', '2025-09-29 23:51:58', 26000.00, '150mg', 'Normal', '[]', '[]'),
(16, 10, 43, 1, 20000.00, '2025-09-29 23:51:58', '2025-09-29 23:51:58', 20000.00, '600ml', 'Normal', '[]', '[]'),
(17, 10, 16, 2, 17600.00, '2025-09-29 23:51:58', '2025-09-29 23:51:58', 8800.00, '350ml', 'Sin azúcar', '[]', '[]'),
(18, 11, 11, 2, 50000.00, '2025-10-02 11:56:58', '2025-10-02 11:56:58', 25000.00, '500ml', 'Extra', '[\"Chocolate\"]', '[\"Crema batida\"]'),
(19, 12, 1, 1, 9200.00, '2025-10-04 03:11:35', '2025-10-04 03:11:35', 9200.00, '450ml', 'Poca', '[]', '[]'),
(20, 12, 38, 3, 21000.00, '2025-10-04 03:11:35', '2025-10-04 03:11:35', 7000.00, NULL, 'Normal', '[]', '[]'),
(21, 13, 11, 2, 50000.00, '2025-10-04 03:15:10', '2025-10-04 03:15:10', 25000.00, '500ml', 'Extra', '[\"Chocolate\"]', '[\"Crema batida\"]'),
(22, 14, 11, 2, 50000.00, '2025-10-04 03:15:20', '2025-10-04 03:15:20', 25000.00, '500ml', 'Extra', '[\"Chocolate\"]', '[\"Crema batida\"]'),
(23, 15, 1, 1, 9200.00, '2025-10-04 03:15:20', '2025-10-04 03:15:20', 9200.00, '450ml', 'Poca', '[]', '[]'),
(24, 15, 38, 3, 21000.00, '2025-10-04 03:15:20', '2025-10-04 03:15:20', 7000.00, NULL, 'Normal', '[]', '[]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `promotional_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'bebidas',
  `has_size` tinyint(1) NOT NULL DEFAULT 0,
  `has_toppings` tinyint(1) NOT NULL DEFAULT 0,
  `has_addons` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `promotional_price`, `stock`, `image`, `created_at`, `updated_at`, `category`, `has_size`, `has_toppings`, `has_addons`) VALUES
(1, 'Espresso', 'Café concentrado y aromático, la esencia del café puro.', 8000.00, NULL, 43, 'espresso.jpg', '2025-09-28 21:02:12', '2025-10-04 03:11:35', '6', 1, 1, 1),
(2, 'Espresso Doble', 'Doble dosis de café espresso para los amantes del café fuerte.', 12000.00, NULL, 35, '/images/products/espresso-doble-68d97c4b930a0.jpg', '2025-09-28 21:02:12', '2025-09-28 21:19:55', '1', 1, 1, 1),
(3, 'Americano', 'Café negro suave y aromático, perfecto para empezar el día.', 9000.00, NULL, 45, '/images/products/americano-68d97c8a94843.jpg', '2025-09-28 21:02:12', '2025-09-29 23:51:58', '1', 1, 1, 1),
(4, 'Capuchino', 'Café espresso con leche vaporizada y espuma de leche, espolvoreado con canela.', 15000.00, NULL, 40, '/images/products/capuchino-68d97cafb610d.jpg', '2025-09-28 21:02:12', '2025-09-28 21:21:35', '1', 1, 1, 1),
(5, 'Latte', 'Café espresso con leche vaporizada y una suave capa de espuma.', 16000.00, NULL, 38, '/images/products/latte-68d97cc30c1c3.jpg', '2025-09-28 21:02:12', '2025-09-28 21:21:55', '1', 1, 1, 1),
(6, 'Mocha', 'Deliciosa combinación de café espresso, chocolate y leche vaporizada.', 18000.00, NULL, 32, '/images/products/mocha-68d97cd8b0824.jpg', '2025-09-28 21:02:12', '2025-09-28 21:22:16', '1', 1, 1, 1),
(7, 'Macchiato', 'Espresso con una pequeña cantidad de leche espumada.', 13000.00, NULL, 28, '/images/products/macchiato-68d97cecc7921.jpg', '2025-09-28 21:02:12', '2025-09-28 21:22:36', '1', 1, 1, 1),
(8, 'Café con Leche', 'La combinación perfecta de café y leche caliente en proporciones equilibradas.', 10000.00, NULL, 54, '/images/products/cafe-con-leche-68d97cf8df84b.jpg', '2025-09-28 21:02:12', '2025-09-29 13:21:54', '1', 1, 1, 1),
(9, 'Cortado', 'Espresso cortado con una pequeña cantidad de leche caliente.', 11000.00, NULL, 30, '/images/products/cortado-68d984cc80000.png', '2025-09-28 21:02:12', '2025-09-28 21:56:12', '1', 1, 1, 1),
(10, 'Iced Coffee', 'Café frío servido con hielo y leche, refrescante y delicioso.', 15000.00, NULL, 25, '/images/products/iced-coffee-68d985f4bc025.png', '2025-09-28 21:02:12', '2025-09-28 22:01:08', '1', 1, 1, 1),
(11, 'Frappé', 'Bebida helada de café batido con hielo y crema batida.', 20000.00, NULL, 20, '/images/products/frappe-68d97d449d117.jpg', '2025-09-28 21:02:12', '2025-10-02 11:56:58', '1', 1, 1, 1),
(12, 'Cold Brew', 'Café infusionado en frío durante 12 horas, suave y menos ácido.', 17000.00, NULL, 20, '/images/products/cold-brew-68d97d584f146.jpg', '2025-09-28 21:02:12', '2025-09-28 21:24:24', '1', 1, 1, 1),
(13, 'Té Negro', 'Té negro aromático de alta calidad, perfecto para cualquier momento.', 7000.00, NULL, 40, '/images/products/te-negro-68d98620a1cc9.jpg', '2025-09-28 21:02:12', '2025-09-28 22:01:52', '2', 1, 0, 1),
(14, 'Té Verde', 'Té verde suave con propiedades antioxidantes.', 7500.00, NULL, 35, '/images/products/te-verde-68d9864b3de9b.png', '2025-09-28 21:02:12', '2025-09-28 22:02:35', '2', 1, 0, 1),
(15, 'Té de Manzanilla', 'Infusión relajante de manzanilla, ideal para calmar y relajar.', 6500.00, NULL, 30, '/images/products/te-de-manzanilla-68d9866b0c214.jpg', '2025-09-28 21:02:12', '2025-09-28 22:03:07', '2', 1, 0, 1),
(16, 'Té de Menta', 'Té refrescante de menta, perfecto para la digestión.', 6800.00, NULL, 26, '/images/products/te-de-menta-68d9869def77a.jpg', '2025-09-28 21:02:12', '2025-09-29 23:51:58', '2', 1, 0, 1),
(17, 'Chocolate Caliente', 'Delicioso chocolate caliente hecho con auténtico cacao y leche cremosa.', 12000.00, NULL, 35, '/images/products/chocolate-caliente-68d986ccf0429.jpg', '2025-09-28 21:02:12', '2025-09-28 22:04:44', '6', 1, 1, 0),
(18, 'Chocolate Blanco', 'Chocolate blanco cremoso con un toque de vainilla.', 13000.00, NULL, 25, '/images/products/chocolate-blanco-68d986edf4190.png', '2025-09-28 21:02:12', '2025-09-28 22:05:18', '6', 1, 1, 0),
(19, 'Submarino', 'Leche caliente con barra de chocolate que se derrite lentamente.', 14000.00, NULL, 20, '/images/products/submarino-68d9870129a3d.jpg', '2025-09-28 21:02:12', '2025-09-28 22:05:37', '6', 1, 1, 0),
(20, 'Croissant', 'Crujiente y esponjoso croissant de manteca, perfecto para acompañar.', 6000.00, NULL, 40, 'croissant.jpg', '2025-09-28 21:02:12', '2025-09-28 21:02:19', '4', 0, 0, 0),
(21, 'Croissant de Jamón y Queso', 'Croissant relleno con jamón y queso derretido.', 10000.00, NULL, 30, '/images/products/croissant-de-jamon-y-queso-68d98716bea53.jpg', '2025-09-28 21:02:12', '2025-09-28 22:06:47', '4', 0, 0, 0),
(22, 'Medialuna de Grasa', 'Clásica medialuna argentina hecha con grasa, liviana y sabrosa.', 5000.00, NULL, 45, '/images/products/medialuna-de-grasa-68d98764499e7.png', '2025-09-28 21:02:12', '2025-09-28 22:07:16', '4', 0, 0, 0),
(23, 'Medialuna de Manteca', 'Medialuna premium elaborada con manteca, extra hojaldrada.', 7000.00, NULL, 35, '/images/products/medialuna-de-manteca-68d9877446354.png', '2025-09-28 21:02:12', '2025-09-28 22:07:32', '4', 0, 0, 0),
(24, 'Porción de Torta Chocolate', 'Torta de chocolate intenso con crema chantilly.', 12000.00, NULL, 25, '/images/products/porcion-de-torta-chocolate-68d98788910f4.jpg', '2025-09-28 21:02:12', '2025-09-28 22:07:52', '3', 1, 1, 0),
(25, 'Porción de Torta Red Velvet', 'Torta suave de terciopelo rojo con frosting de queso crema.', 14000.00, NULL, 20, '/images/products/porcion-de-torta-red-velvet-68d9e7c2c24fd.jpg', '2025-09-28 21:02:12', '2025-09-29 00:58:26', '3', 0, 0, 0),
(26, 'Porción de Cheesecake', 'Cheesecake cremoso con base de galleta y frutos rojos.', 15000.00, NULL, 18, '/images/products/porcion-de-cheesecake-68d9ea63eee58.jpg', '2025-09-28 21:02:12', '2025-09-29 01:09:39', '3', 0, 0, 0),
(27, 'Brownie', 'Brownie de chocolate intenso con nueces y salsa de chocolate.', 9000.00, NULL, 30, '/images/products/brownie-68d9eb773be9e.jpg', '2025-09-28 21:02:12', '2025-09-29 01:14:15', '3', 0, 0, 0),
(28, 'Porción de Lemon Pie', 'Tarta de limón con merengue italiano, balance perfecto entre dulce y ácido.', 13000.00, NULL, 22, '/images/products/porcion-de-lemon-pie-68d9884240bd2.png', '2025-09-28 21:02:12', '2025-09-28 22:10:58', '3', 0, 0, 0),
(29, 'Sandwich de Jamón y Queso', 'Clásico sandwich de jamón y queso en pan ciabatta tostado.', 15000.00, NULL, 35, '/images/products/sandwich-de-jamon-y-queso-68dbc4f34ccc1.jpg', '2025-09-28 21:02:12', '2025-09-30 10:54:27', '4', 0, 0, 1),
(30, 'Sandwich Club', 'Sandwich triple con pollo, panceta, lechuga, tomate y mayonesa.', 22000.00, NULL, 25, '/images/products/sandwich-club-68dbc54a4bcae.jpg', '2025-09-28 21:02:12', '2025-09-30 10:55:54', '4', 0, 0, 1),
(31, 'Tostado Mixto', 'Tostado caliente de jamón y queso derretido.', 12000.00, NULL, 40, '/images/products/tostado-mixto-68dbc5dd14d83.jpg', '2025-09-28 21:02:12', '2025-09-30 10:58:21', '4', 0, 0, 1),
(32, 'Empanada de Carne', 'Empanada horneada rellena de carne jugosa con aceitunas y huevo.', 8000.00, NULL, 50, '/images/products/empanada-de-carne-68de70689766a.jpg', '2025-09-28 21:02:12', '2025-10-02 11:30:32', '7', 0, 0, 1),
(33, 'Empanada de Jamón y Queso', 'Empanada horneada rellena de jamón y queso derretido.', 7500.00, NULL, 45, '/images/products/empanada-de-jamon-y-queso-68de7080b72e1.jpg', '2025-09-28 21:02:12', '2025-10-02 11:30:56', '7', 0, 0, 1),
(34, 'Empanada de Pollo', 'Empanada horneada rellena de pollo cremoso con verduras.', 8000.00, NULL, 40, '/images/products/empanada-de-pollo-68de70a68da80.jpg', '2025-09-28 21:02:12', '2025-10-02 11:31:34', '7', 0, 0, 1),
(35, 'Factura', 'Factura clásica de manteca espolvoreada con azúcar.', 4500.00, NULL, 60, '/images/products/factura-68d98975a7692.png', '2025-09-28 21:02:12', '2025-10-02 11:32:56', '8', 0, 0, 0),
(36, 'Cañoncito', 'Factura rellena de dulce de leche o crema pastelera.', 6000.00, NULL, 34, '/images/products/canoncito-68d989882e3b6.png', '2025-09-28 21:02:12', '2025-10-02 11:33:14', '8', 0, 0, 0),
(37, 'Vigilante', 'Dos galletas de manteca unidas con dulce de membrillo.', 5000.00, NULL, 30, '/images/products/vigilante-68d989a1f1529.png', '2025-09-28 21:02:12', '2025-10-02 11:33:35', '8', 0, 0, 0),
(38, 'Alfajor', 'Alfajor de maicena relleno de dulce de leche y coco.', 7000.00, NULL, 31, '/images/products/alfajor-68d98c06e5164.png', '2025-09-28 21:02:12', '2025-10-04 03:11:35', '8', 0, 0, 0),
(39, 'Galletas de Avena', 'Galletas saludables de avena con pasas de uva y miel.', 5500.00, NULL, 45, '/images/products/galletas-de-avena-68de72c29390d.jpg', '2025-09-28 21:02:12', '2025-10-02 11:40:34', '4', 0, 0, 0),
(40, 'Agua Mineral 500ml', 'Agua mineral natural sin gas.', 5000.00, NULL, 97, '/images/products/agua-mineral-500ml-68de733bf1e85.jpg', '2025-09-28 21:02:12', '2025-10-02 11:42:35', '9', 0, 0, 0),
(41, 'Jugo de Naranja Natural', 'Jugo de naranja exprimido al momento, rico en vitamina C.', 10000.00, NULL, 30, '/images/products/jugo-de-naranja-natural-68de73d94898d.jpg', '2025-09-28 21:02:12', '2025-10-02 11:45:13', '6', 0, 0, 0),
(42, 'Limonada', 'Limonada natural refrescante con hierbabuena. img', 9000.00, NULL, 35, '/images/products/limonada-68d99c6bdb3c7.png', '2025-09-28 21:02:12', '2025-09-28 23:37:00', '6', 0, 0, 0),
(43, 'Smoothie de Frutilla', 'Smoothie cremoso de frutilla con yogurt natural.', 16000.00, NULL, 24, '/images/products/smoothie-de-frutilla-68d99f1d169f6.png', '2025-09-28 21:02:12', '2025-10-02 11:44:01', '6', 0, 0, 0),
(44, 'Ensalada Mediterránea', 'Mezcla fresca de lechuga, tomates cherry, aceitunas negras, queso feta y aderezo de oliva. img', 20000.00, NULL, 17, '/images/products/ensalada-mediterranea-68db0d42c88a1.png', '2025-09-29 16:15:30', '2025-09-29 23:51:58', '5', 0, 0, 0),
(45, 'Bagel con Queso Crema', 'Pan redondo estilo bagel, tostado y relleno con suave queso crema. img', 13000.00, NULL, 20, '/images/products/bagel-con-queso-crema-68db0f0b5f5d8.png', '2025-09-29 16:16:56', '2025-09-29 21:58:19', '8', 0, 0, 0),
(46, 'Muffin de Arándanos', 'Esponjoso muffin con arándanos frescos y un toque de limón.', 11000.00, NULL, 28, '/images/products/muffin-de-arandanos-68db117ca501f.png', '2025-09-29 16:17:55', '2025-10-02 11:38:54', '4', 0, 0, 0),
(47, 'Té Chai Latte', 'Infusión especiada de té negro con leche vaporizada y canela. img', 12000.00, NULL, 30, '/images/products/te-chai-latte-68db12f549e75.png', '2025-09-29 16:18:43', '2025-09-29 22:15:01', '2', 0, 0, 0),
(48, 'Sandwich Integral de Pollo', 'Pan integral relleno con pollo grillado, lechuga, tomate y aderezo ligero. img', 18000.00, NULL, 28, '/images/products/sandwich-integral-de-pollo-68db14ca829b9.png', '2025-09-29 16:19:43', '2025-09-29 22:22:50', '4', 0, 0, 0),
(49, 'Rebanada de Tarta de Manzana', 'Tarta casera con manzanas caramelizadas y masa crujiente.', 13000.00, NULL, 22, '/images/products/rebanada-de-tarta-de-manzana-68db16a95fc6a.png', '2025-09-29 16:20:49', '2025-10-02 11:46:34', '3', 0, 0, 0),
(50, 'Donut Glaseada', 'Donut esponjosa cubierta con glaseado de azúcar brillante. img', 8000.00, NULL, 26, '/images/products/donut-glaseada-68db18c9e0a8f.png', '2025-09-29 16:23:39', '2025-09-29 22:39:53', '8', 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_addons`
--

CREATE TABLE `product_addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `has_quantity` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `product_addons`
--

INSERT INTO `product_addons` (`id`, `product_id`, `name`, `price`, `has_quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(2, 1, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(3, 1, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(4, 1, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(5, 2, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(6, 2, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(7, 2, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(8, 2, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(9, 3, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(10, 3, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(11, 3, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(12, 3, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(13, 4, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(14, 4, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(15, 4, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(16, 4, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(17, 5, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(18, 5, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(19, 5, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(20, 5, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(21, 6, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(22, 6, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(23, 6, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(24, 6, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(25, 7, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(26, 7, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(27, 7, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(28, 7, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(29, 8, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(30, 8, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(31, 8, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(32, 8, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(33, 9, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(34, 9, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(35, 9, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(36, 9, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(37, 10, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(38, 10, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(39, 10, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(40, 10, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(41, 11, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(42, 11, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(43, 11, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(44, 11, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(45, 12, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(46, 12, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(47, 12, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(48, 12, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(49, 13, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(50, 13, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(51, 13, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(52, 13, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(53, 14, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(54, 14, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(55, 14, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(56, 14, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(57, 15, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(58, 15, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(59, 15, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(60, 15, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(61, 16, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(62, 16, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(63, 16, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(64, 16, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(65, 29, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(66, 29, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(67, 29, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(68, 29, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(69, 30, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(70, 30, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(71, 30, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(72, 30, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(73, 31, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(74, 31, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(75, 31, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(76, 31, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(77, 32, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(78, 32, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(79, 32, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(80, 32, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(81, 33, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(82, 33, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(83, 33, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(84, 33, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(85, 34, 'Leche Extra', 1000.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(86, 34, 'Azúcar Extra', 500.00, 1, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(87, 34, 'Crema Batida', 2000.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22'),
(88, 34, 'Hielo Extra', 500.00, 0, '2025-09-28 21:02:22', '2025-09-28 21:02:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_customization_rules`
--

CREATE TABLE `product_customization_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `enabled_options` longtext NOT NULL CHECK (json_valid(`enabled_options`)),
  `size_options` longtext DEFAULT NULL CHECK (json_valid(`size_options`)),
  `default_size` varchar(255) DEFAULT NULL,
  `sugar_options` longtext DEFAULT NULL CHECK (json_valid(`sugar_options`)),
  `topping_options` longtext DEFAULT NULL CHECK (json_valid(`topping_options`)),
  `addon_options` longtext DEFAULT NULL CHECK (json_valid(`addon_options`)),
  `excluded_products` longtext DEFAULT NULL CHECK (json_valid(`excluded_products`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `product_customization_rules`
--

INSERT INTO `product_customization_rules` (`id`, `category_id`, `enabled_options`, `size_options`, `default_size`, `sugar_options`, `topping_options`, `addon_options`, `excluded_products`, `created_at`, `updated_at`) VALUES
(3, 1, '[\"quantity\",\"size\",\"sugar\",\"addons\",\"toppings\"]', '[{\"name\":\"250ml\",\"price\":0},{\"name\":\"350ml\",\"price\":2000},{\"name\":\"500ml\",\"price\":4000}]', '250ml', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":1000}]', '[]', '2025-09-29 11:33:43', '2025-09-29 11:33:43'),
(4, 2, '[\"quantity\",\"size\",\"sugar\"]', '[{\"name\":\"250ml\",\"price\":0},{\"name\":\"350ml\",\"price\":2000}]', '250ml', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":500}]', '[]', '2025-09-29 16:45:33', '2025-09-29 16:45:33'),
(6, 3, '[\"quantity\"]', '[{\"name\":\"Peque\\u00f1o\",\"price\":0},{\"name\":\"Mediano\",\"price\":2000},{\"name\":\"Grande\",\"price\":4000}]', 'Mediano', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":500}]', '[]', '2025-09-29 16:46:55', '2025-09-29 16:46:55'),
(7, 4, '[\"quantity\"]', '[{\"name\":\"Peque\\u00f1o\",\"price\":0},{\"name\":\"Mediano\",\"price\":2000},{\"name\":\"Grande\",\"price\":4000}]', 'Mediano', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":500}]', '[]', '2025-09-29 16:47:04', '2025-09-29 16:47:04'),
(9, 5, '[\"size\"]', '[{\"name\":\"50mg\",\"price\":0},{\"name\":\"85mg\",\"price\":2000},{\"name\":\"150mg\",\"price\":6000}]', '50mg', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":500}]', '[]', '2025-09-29 16:50:04', '2025-09-29 16:50:04'),
(10, 6, '[\"quantity\",\"size\",\"sugar\"]', '[{\"name\":\"350ml\",\"price\":0},{\"name\":\"450ml\",\"price\":2000},{\"name\":\"600ml\",\"price\":4000}]', '350ml', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":500}]', '[40]', '2025-09-29 16:51:20', '2025-09-29 16:51:20'),
(11, 7, '[\"quantity\"]', '[{\"name\":\"Peque\\u00f1o\",\"price\":0},{\"name\":\"Mediano\",\"price\":2000},{\"name\":\"Grande\",\"price\":4000}]', 'Mediano', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":500}]', '[]', '2025-09-29 16:51:47', '2025-09-29 16:51:47'),
(12, 8, '[\"quantity\"]', '[{\"name\":\"Peque\\u00f1o\",\"price\":0},{\"name\":\"Mediano\",\"price\":2000},{\"name\":\"Grande\",\"price\":4000}]', 'Mediano', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":500}]', '[]', '2025-09-29 16:52:02', '2025-09-29 16:52:02'),
(15, 9, '[\"quantity\"]', '[{\"name\":\"Peque\\u00f1o\",\"price\":0},{\"name\":\"Mediano\",\"price\":2000},{\"name\":\"Grande\",\"price\":4000}]', 'Mediano', '[\"Sin az\\u00facar\",\"Poca\",\"Normal\",\"Extra\"]', '[{\"name\":\"Chocolate\",\"price\":2000},{\"name\":\"Vainilla\",\"price\":2000},{\"name\":\"Caramelo\",\"price\":3000}]', '[{\"name\":\"Leche extra\",\"price\":1000},{\"name\":\"Crema batida\",\"price\":2000},{\"name\":\"Canela\",\"price\":500}]', '[]', '2025-10-03 21:13:43', '2025-10-03 21:13:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_id`, `size`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(2, 1, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(3, 1, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(4, 2, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(5, 2, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(6, 2, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(7, 3, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(8, 3, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(9, 3, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(10, 4, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(11, 4, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(12, 4, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(13, 5, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(14, 5, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(15, 5, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(16, 6, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(17, 6, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(18, 6, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(19, 7, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(20, 7, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(21, 7, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(22, 8, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(23, 8, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(24, 8, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(25, 9, 'Pequeño', 0.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(26, 9, 'Mediano', 2000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(27, 9, 'Grande', 4000.00, '2025-09-28 21:02:19', '2025-09-28 21:02:19'),
(28, 10, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(29, 10, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(30, 10, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(31, 11, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(32, 11, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(33, 11, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(34, 12, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(35, 12, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(36, 12, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(37, 13, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(38, 13, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(39, 13, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(40, 14, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(41, 14, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(42, 14, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(43, 15, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(44, 15, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(45, 15, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(46, 16, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(47, 16, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(48, 16, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(49, 17, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(50, 17, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(51, 17, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(52, 18, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(53, 18, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(54, 18, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(55, 19, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(56, 19, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(57, 19, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(58, 24, 'Pequeño', 0.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(59, 24, 'Mediano', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(60, 24, 'Grande', 4000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_toppings`
--

CREATE TABLE `product_toppings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `product_toppings`
--

INSERT INTO `product_toppings` (`id`, `product_id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Chocolate', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(2, 1, 'Vainilla', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(3, 1, 'Caramelo', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(4, 1, 'Canela', 1000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(5, 2, 'Chocolate', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(6, 2, 'Vainilla', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(7, 2, 'Caramelo', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(8, 2, 'Canela', 1000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(9, 3, 'Chocolate', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(10, 3, 'Vainilla', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(11, 3, 'Caramelo', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(12, 3, 'Canela', 1000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(13, 4, 'Chocolate', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(14, 4, 'Vainilla', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(15, 4, 'Caramelo', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(16, 4, 'Canela', 1000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(17, 5, 'Chocolate', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(18, 5, 'Vainilla', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(19, 5, 'Caramelo', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(20, 5, 'Canela', 1000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(21, 6, 'Chocolate', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(22, 6, 'Vainilla', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(23, 6, 'Caramelo', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(24, 6, 'Canela', 1000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(25, 7, 'Chocolate', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(26, 7, 'Vainilla', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(27, 7, 'Caramelo', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(28, 7, 'Canela', 1000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(29, 8, 'Chocolate', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(30, 8, 'Vainilla', 1500.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(31, 8, 'Caramelo', 2000.00, '2025-09-28 21:02:20', '2025-09-28 21:02:20'),
(32, 8, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(33, 9, 'Chocolate', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(34, 9, 'Vainilla', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(35, 9, 'Caramelo', 2000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(36, 9, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(37, 10, 'Chocolate', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(38, 10, 'Vainilla', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(39, 10, 'Caramelo', 2000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(40, 10, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(41, 11, 'Chocolate', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(42, 11, 'Vainilla', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(43, 11, 'Caramelo', 2000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(44, 11, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(45, 12, 'Chocolate', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(46, 12, 'Vainilla', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(47, 12, 'Caramelo', 2000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(48, 12, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(49, 17, 'Chocolate', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(50, 17, 'Vainilla', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(51, 17, 'Caramelo', 2000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(52, 17, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(53, 18, 'Chocolate', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(54, 18, 'Vainilla', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(55, 18, 'Caramelo', 2000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(56, 18, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(57, 19, 'Chocolate', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(58, 19, 'Vainilla', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(59, 19, 'Caramelo', 2000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(60, 19, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(61, 24, 'Chocolate', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(62, 24, 'Vainilla', 1500.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(63, 24, 'Caramelo', 2000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21'),
(64, 24, 'Canela', 1000.00, '2025-09-28 21:02:21', '2025-09-28 21:02:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `start_date` datetime DEFAULT current_timestamp(),
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `promotions`
--

INSERT INTO `promotions` (`id`, `product_id`, `discount_percentage`, `original_price`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(25, 1, 10.00, 8000.00, NULL, NULL, 1, '2025-10-03 20:35:48', '2025-10-03 20:35:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('gJbDJ3pxZViKOOHzaFpuON9D7uCsU1JElmI804mb', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVVVsMHJaU0F5ZEpld2NhUHpvd3B6YlhSRVdUM0hXRWdBdmFEZEcyayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760032323),
('ObV8bj86sbVjKZAaVdlMtnBYa0CDwv5n11HErFpC', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYkx3MGdiMEhRSEQ0RnljUTFOdzk2TkpPRGR6YmtFQUJ1YkJKWjh1RSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1759553629);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_settings`
--

CREATE TABLE `shop_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_name` varchar(255) NOT NULL DEFAULT 'Daylen Cafetería',
  `logo_url` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT 'daylencoffee@gmail.com',
  `phone` varchar(255) NOT NULL DEFAULT '+595 986 195914',
  `address` text,
  `ruc` varchar(255) NOT NULL DEFAULT '12345678-9',
  `about_us` text,
  `primary_color` varchar(255) NOT NULL DEFAULT '#d97706',
  `secondary_color` varchar(255) NOT NULL DEFAULT '#f59e0b',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `shop_settings`
--

INSERT INTO `shop_settings` (`id`, `shop_name`, `logo_url`, `email`, `phone`, `address`, `ruc`, `about_us`, `primary_color`, `secondary_color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Daylen Cafetería', '/images/logo/logo-68de70379aab9.png', 'daylencoffee@gmail.com', '+595 986 195914', 'Calle Carlos Gómez, Barrio Remansito Sector 5, Ciudad del Este', '7377816-5', 'Somos una cafetería dedicada a ofrecer los mejores productos con ingredientes de calidad. Comprometidos con la satisfacción de nuestros clientes en busca de una constante mejora.', '#d97706', '#f59e0b', 1, '2025-09-30 01:14:05', '2025-10-04 03:01:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@daylen.com', NULL, '$2y$12$nFO6wAJCpgPjwsqO4oeYJOAiKRheR0Xozk1UZqHhPhLB7wXa.R9wq', 1, NULL, '2025-09-29 01:39:12', '2025-09-29 01:39:12'),
(2, 'User', 'user@daylen.com', NULL, '$2y$12$.uA1JstJMwihO6Xj8MZf.ecGe5ltWGzrMZE0CaeZTwxouiJ7pwJX.', 0, NULL, '2025-09-29 01:39:12', '2025-09-29 01:39:12'),
(3, 'Aye', 'ayeleennvera@gmail.com', NULL, '$2y$12$nrW7r4UQP1UXMvBYm9aP.ey/uQQkSY90FOluNeSv.4LYvFDmbZoim', 0, NULL, '2025-09-29 00:45:21', '2025-09-29 00:45:21');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product_addons`
--
ALTER TABLE `product_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_addons_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `product_customization_rules`
--
ALTER TABLE `product_customization_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_customization_rules_category_id_foreign` (`category_id`);

--
-- Indices de la tabla `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_sizes_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `product_toppings`
--
ALTER TABLE `product_toppings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_toppings_product_id_foreign` (`product_id`);

--
-- Indices de la tabla `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_dates` (`start_date`,`end_date`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `shop_settings`
--
ALTER TABLE `shop_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `product_addons`
--
ALTER TABLE `product_addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `product_customization_rules`
--
ALTER TABLE `product_customization_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `product_toppings`
--
ALTER TABLE `product_toppings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `shop_settings`
--
ALTER TABLE `shop_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `product_customization_rules`
--
ALTER TABLE `product_customization_rules`
  ADD CONSTRAINT `product_customization_rules_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
