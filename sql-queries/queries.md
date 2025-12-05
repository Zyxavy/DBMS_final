# SQL

- Delete existing database if you want to update
```sql
    DROP DATABASE IF EXISTS ecommerce_db;
```

- Create Database and tables
```sql
CREATE DATABASE ecommerce_db

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('customer', 'admin') DEFAULT 'customer',
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    INDEX idx_user_id (user_id),
    INDEX idx_email (email),
    INDEX idx_username (username)
);

- Creating the table products, class and category
CREATE TABLE class (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name varchar(255) NOT NULL
);
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name varchar(255) NOT NULL
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    class_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
    ROM INT,
    RAM int,

    FOREIGN KEY (category_id) REFERENCES category(id),
    FOREIGN KEY (class_id) REFERENCES class(id),

    INDEX idx_name (name),
    INDEX idx_category (category_id),
    INDEX idx_class (class_id),
    INDEX idx_price (price)
);
```