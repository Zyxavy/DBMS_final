# SQL

 //Based a figma UI ma add pa kita hin extra columns

- Delete existing database if you want to update
```sql
    DROP DATABASE IF EXISTS ecommerce_db;
```

- Create Database and tables
```sql
CREATE DATABASE ecommerce_db;
USE ecommerce_db;
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('customer', 'admin') DEFAULT 'customer',
    username VARCHAR(100) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(11) NOT NULL UNIQUE,
    INDEX idx_email (email),
    INDEX idx_username (username)
);

CREATE TABLE class (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name varchar(255) NOT NULL,
    INDEX idx_class_name (class_name)
);
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name varchar(255) NOT NULL,
    INDEX idx_category_name (category_name)
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    product_description TEXT,
    category_id INT NOT NULL,
    class_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
    img_url VARCHAR(255),
    ROM INT,
    RAM int,

    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES class(id) ON DELETE CASCADE,

    INDEX idx_name (name),
    INDEX idx_category (category_id),
    INDEX idx_class (class_id),
    INDEX idx_price (price),
    INDEX idx_rom (ROM),
    INDEX idx_ram (RAM)
);

CREATE TABLE address(
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_type ENUM('home', 'work') DEFAULT 'home',
    street_address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    province VARCHAR(255) NOT NULL,
    postal_code VARCHAR(10)NOT NULL,
    unit_num VARCHAR(100) NOT NULL,
    is_default BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_address(user_id)
);

CREATE TABLE orders(
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL CHECK (total_amount >= 0),
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('credit_card', 'debit_card', 'gcash', 'paymaya', 'cash_on_delivery') DEFAULT 'cash_on_delivery',
    payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (address_id) REFERENCES address(address_id) ON DELETE RESTRICT,
    INDEX idx_user_orders (user_id),
    INDEX idx_order_date (order_date),
    INDEX idx_status (order_status)
);

CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    unit_price DECIMAL(10, 2) NOT NULL CHECK (unit_price >= 0),
    subtotal_price DECIMAL(10, 2) NOT NULL CHECK (subtotal_price >= 0),
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
);

CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    INDEX idx_user_cart (user_id),
    INDEX idx_product (product_id)
);
```