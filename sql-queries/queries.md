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

```