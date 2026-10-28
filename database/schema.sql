-- Kampung Cameron E-Commerce Database Schema
-- UTF8MB4 for full bilingual support (English & Simplified Chinese)

CREATE DATABASE IF NOT EXISTS `kampung_cameron` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kampung_cameron`;

-- Users table (Customers, Wholesale clients, Admins)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `role` ENUM('customer', 'wholesale', 'admin') NOT NULL DEFAULT 'customer',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Delivery Addresses
CREATE TABLE IF NOT EXISTS `addresses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `label` VARCHAR(60) DEFAULT 'Home',
    `recipient_name` VARCHAR(120) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `address_line1` VARCHAR(255) NOT NULL,
    `address_line2` VARCHAR(255) DEFAULT NULL,
    `city` VARCHAR(80) NOT NULL,
    `state` VARCHAR(60) NOT NULL,
    `postcode` VARCHAR(20) NOT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `name_en` VARCHAR(120) NOT NULL,
    `name_zh` VARCHAR(120) NOT NULL,
    `description_en` TEXT DEFAULT NULL,
    `description_zh` TEXT DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    INDEX `idx_cat_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pricing Tiers (for B2B Wholesale)
CREATE TABLE IF NOT EXISTS `pricing_tiers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(60) NOT NULL,
    `code` VARCHAR(30) NOT NULL UNIQUE,
    `discount_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `description` VARCHAR(255) DEFAULT NULL,
    `min_spend` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `sku` VARCHAR(60) NOT NULL UNIQUE,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `name_en` VARCHAR(150) NOT NULL,
    `name_zh` VARCHAR(150) NOT NULL,
    `unit_weight` VARCHAR(60) NOT NULL DEFAULT '500g',
    `price` DECIMAL(10,2) NOT NULL,
    `sale_price` DECIMAL(10,2) DEFAULT NULL,
    `stock_quantity` INT NOT NULL DEFAULT 0,
    `low_stock_threshold` INT NOT NULL DEFAULT 5,
    `description_en` TEXT DEFAULT NULL,
    `description_zh` TEXT DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `is_best_seller` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT,
    INDEX `idx_prod_category` (`category_id`),
    INDEX `idx_prod_slug` (`slug`),
    INDEX `idx_prod_stock` (`stock_quantity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wholesale Accounts (B2B business verification)
CREATE TABLE IF NOT EXISTS `wholesale_accounts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `business_name` VARCHAR(150) NOT NULL,
    `registration_number` VARCHAR(60) NOT NULL,
    `contact_person` VARCHAR(120) NOT NULL,
    `contact_email` VARCHAR(150) NOT NULL,
    `contact_phone` VARCHAR(30) NOT NULL,
    `business_type` VARCHAR(80) DEFAULT 'Restaurant / Kitchen',
    `document_path` VARCHAR(255) DEFAULT NULL,
    `tier_id` INT DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `admin_notes` TEXT DEFAULT NULL,
    `approved_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`tier_id`) REFERENCES `pricing_tiers`(`id`) ON DELETE SET NULL,
    INDEX `idx_ws_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standing Orders (Recurring wholesale schedule)
CREATE TABLE IF NOT EXISTS `standing_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `wholesale_account_id` INT NOT NULL,
    `name` VARCHAR(120) NOT NULL,
    `delivery_day` ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL DEFAULT 'Monday',
    `delivery_address` TEXT NOT NULL,
    `start_date` DATE NOT NULL,
    `notes` TEXT DEFAULT NULL,
    `status` ENUM('active', 'paused', 'cancelled') NOT NULL DEFAULT 'active',
    `last_generated_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`wholesale_account_id`) REFERENCES `wholesale_accounts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standing Order Items
CREATE TABLE IF NOT EXISTS `standing_order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `standing_order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    FOREIGN KEY (`standing_order_id`) REFERENCES `standing_orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Stock History Log
CREATE TABLE IF NOT EXISTS `stock_log` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `change_qty` INT NOT NULL,
    `balance_qty` INT NOT NULL,
    `reason` VARCHAR(100) NOT NULL,
    `reference_id` VARCHAR(80) DEFAULT NULL,
    `created_by` VARCHAR(100) DEFAULT 'system',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_stock_prod` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Promo Codes
CREATE TABLE IF NOT EXISTS `promo_codes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `discount_type` ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    `discount_value` DECIMAL(10,2) NOT NULL,
    `min_order_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `max_uses` INT DEFAULT NULL,
    `uses_count` INT NOT NULL DEFAULT 0,
    `starts_at` DATETIME DEFAULT NULL,
    `expires_at` DATETIME DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Active Shopping Carts
CREATE TABLE IF NOT EXISTS `carts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `session_id` VARCHAR(128) NOT NULL,
    `promo_code_id` INT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`promo_code_id`) REFERENCES `promo_codes`(`id`) ON DELETE SET NULL,
    INDEX `idx_cart_session` (`session_id`),
    INDEX `idx_cart_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cart Items
CREATE TABLE IF NOT EXISTS `cart_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `cart_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_cart_prod` (`cart_id`, `product_id`),
    FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Customer Wishlist
CREATE TABLE IF NOT EXISTS `wishlist` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_user_wishlist` (`user_id`, `product_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` INT DEFAULT NULL,
    `order_type` ENUM('retail', 'wholesale', 'standing_order') NOT NULL DEFAULT 'retail',
    `status` ENUM('pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    `subtotal` DECIMAL(10,2) NOT NULL,
    `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `shipping_fee` DECIMAL(10,2) NOT NULL DEFAULT 8.00,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `payment_method` ENUM('fpx', 'card', 'grabpay', 'tng', 'cod') NOT NULL DEFAULT 'fpx',
    `payment_status` ENUM('selected', 'pending', 'confirmed') NOT NULL DEFAULT 'selected',
    `customer_name` VARCHAR(120) NOT NULL,
    `customer_email` VARCHAR(150) NOT NULL,
    `customer_phone` VARCHAR(30) NOT NULL,
    `delivery_address` VARCHAR(255) NOT NULL,
    `delivery_line2` VARCHAR(255) DEFAULT NULL,
    `delivery_city` VARCHAR(80) NOT NULL,
    `delivery_state` VARCHAR(60) NOT NULL,
    `delivery_postcode` VARCHAR(20) NOT NULL,
    `delivery_notes` TEXT DEFAULT NULL,
    `promo_code` VARCHAR(50) DEFAULT NULL,
    `standing_order_id` INT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`standing_order_id`) REFERENCES `standing_orders`(`id`) ON DELETE SET NULL,
    INDEX `idx_order_num` (`order_number`),
    INDEX `idx_order_status` (`status`),
    INDEX `idx_order_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Items
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `unit_weight` VARCHAR(60) NOT NULL,
    `unit_price` DECIMAL(10,2) NOT NULL,
    `quantity` INT NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product Reviews
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `order_id` INT DEFAULT NULL,
    `rating` TINYINT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `review_text` TEXT NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL,
    INDEX `idx_rev_prod` (`product_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recipes (Veggie Recipe & Health Nourishment)
CREATE TABLE IF NOT EXISTS `recipes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `category` ENUM('veggie_recipe', 'health_nourishment') NOT NULL DEFAULT 'veggie_recipe',
    `title_en` VARCHAR(180) NOT NULL,
    `title_zh` VARCHAR(180) NOT NULL,
    `prep_time_minutes` INT NOT NULL DEFAULT 15,
    `cooking_time_minutes` INT NOT NULL DEFAULT 15,
    `servings` VARCHAR(40) NOT NULL DEFAULT '2-4 portions',
    `image` VARCHAR(255) DEFAULT NULL,
    `excerpt_en` TEXT DEFAULT NULL,
    `excerpt_zh` TEXT DEFAULT NULL,
    `ingredients_en` TEXT NOT NULL,
    `ingredients_zh` TEXT NOT NULL,
    `steps_en` MEDIUMTEXT NOT NULL,
    `steps_zh` MEDIUMTEXT NOT NULL,
    `is_published` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_rec_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recipe Products association (for "Add all to cart")
CREATE TABLE IF NOT EXISTS `recipe_products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `recipe_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    UNIQUE KEY `uk_rec_prod` (`recipe_id`, `product_id`),
    FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Marketing Banners
CREATE TABLE IF NOT EXISTS `banners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title_en` VARCHAR(150) NOT NULL,
    `title_zh` VARCHAR(150) NOT NULL,
    `subtitle_en` VARCHAR(255) DEFAULT NULL,
    `subtitle_zh` VARCHAR(255) DEFAULT NULL,
    `link_url` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Store Settings
CREATE TABLE IF NOT EXISTS `settings` (
    `setting_key` VARCHAR(60) PRIMARY KEY,
    `setting_value` TEXT NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
