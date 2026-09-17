-- ============================================
-- Commerce Project - Database Schema
-- LAMPP Stack (MySQL)
-- ============================================

DROP DATABASE IF EXISTS divc_ecommerce_db;
CREATE DATABASE divc_ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE divc_ecommerce_db;

-- ============================================
-- users
-- ============================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    user_type ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    deleted_at DATETIME NULL,
    UNIQUE KEY uq_users_username (username),
    KEY idx_users_deleted_at (deleted_at)
);

-- ============================================
-- user_profiles (1:1 with users)
-- ============================================
CREATE TABLE user_profiles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NULL,
    mobile_number VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    deleted_at DATETIME NULL,
    UNIQUE KEY uq_user_profiles_user_id (user_id),
    KEY idx_user_profiles_deleted_at (deleted_at),
    CONSTRAINT fk_user_profiles_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- ============================================
-- categories
-- ============================================
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    deleted_at DATETIME NULL,
    KEY idx_categories_deleted_at (deleted_at)
);

-- ============================================
-- products
-- ============================================
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT NULL,
    deleted_at DATETIME NULL,
    KEY idx_products_category_id (category_id),
    KEY idx_products_deleted_at (deleted_at),
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- ============================================
-- orders (1:M with users)
-- ============================================
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    placed_at DATETIME NULL,
    invoice_number VARCHAR(50) NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('in cart', 'processing', 'on the way', 'delivered') NOT NULL DEFAULT 'in cart',
    deleted_at DATETIME NULL,
    KEY idx_orders_user_id (user_id),
    KEY idx_orders_deleted_at (deleted_at),
    UNIQUE KEY uq_orders_invoice_number (invoice_number),
    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- ============================================
-- ordered_products (M:M between orders and products)
-- ============================================
CREATE TABLE ordered_products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    single_amount DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    deleted_at DATETIME NULL,
    KEY idx_ordered_products_order_id (order_id),
    KEY idx_ordered_products_product_id (product_id),
    KEY idx_ordered_products_deleted_at (deleted_at),
    CONSTRAINT fk_ordered_products_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT fk_ordered_products_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);