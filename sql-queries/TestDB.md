# SQL

- Delete existing database if you want to update
```sql
    DROP DATABASE IF EXISTS proto_ecommerce_db;
```

- Create Database and tables
```sql
CREATE DATABASE proto_ecommerce_db

- To delete the table name users
```
DROP TABLE users;
```
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    user_type ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

```