
```php
INSERT IGNORE INTO category (category_name) VALUES ('Phones');
INSERT IGNORE INTO class (class_name) VALUES ('Smartphones');

INSERT INTO products (name, product_description, category_id, class_id, price, stock, img_url, ROM, RAM) VALUES
(
    'M-phone 17', '48MP Main | Ultra Wide<br>Super Retina XDR Display, A16 Bionic Chip, 20h Video Playback', (SELECT id FROM category WHERE category_name = 'Phones'),
    (SELECT id FROM class WHERE class_name = 'Smartphones'), 79999.00, 50, '../assets/Phones/iphone17.png',128, 8         
),

(
    'M-phone 17 Air',
    '48MP Main | Ultra Wide<br>Lightweight Design, M15 Bionic Chip, 18h Video Playback',
    (SELECT id FROM category WHERE category_name = 'Phones'),(SELECT id FROM class WHERE class_name = 'Smartphones'),69999.00,  
    75,  '../assets/Phones/Iphone17_air.png', 256, 8         
),

(
    'M-phone 17 Pro','48MP Main | Ultra Wide | Telephoto<br>ProMotion Technology, A17 Pro Chip, 29h Video Playback',
    (SELECT id FROM category WHERE category_name = 'Phones'), (SELECT id FROM class WHERE class_name = 'Smartphones'),
    99999.00, 30, '../assets/Phones/Iphone17Pro.png',512, 12        
);

INSERT IGNORE INTO category (category_name) VALUES ('Laptops');
INSERT IGNORE INTO category (category_name) VALUES ('Desktops');

INSERT IGNORE INTO class (class_name) VALUES ('Entry level');
INSERT IGNORE INTO class (class_name) VALUES ('Mid tier');
INSERT IGNORE INTO class (class_name) VALUES ('Flag ship');


INSERT INTO products (name, product_description, category_id, class_id, price, stock, img_url, ROM, RAM)
SELECT 
    'M-Book Air',
    'M2 Chip<br>18hr Battery Life<br>1.24 kg Weight<br>13.6" Liquid Retina',
    (SELECT id FROM category WHERE category_name = 'Laptops'),
    (SELECT id FROM class WHERE class_name = 'Entry level'),
    49999.00, 30, '../assets/PC/macbook-air.png', 256, 8
WHERE EXISTS (SELECT 1 FROM category WHERE category_name = 'Laptops')
AND EXISTS (SELECT 1 FROM class WHERE class_name = 'Entry level');

INSERT INTO products (name, product_description, category_id, class_id, price, stock, img_url, ROM, RAM)
SELECT 
    'M-Book Pro 14',
    'M3 Pro Chip<br>22hr Battery Life<br>Up to 36GB Unified Memory<br>14.2" XDR Display',
    (SELECT id FROM category WHERE category_name = 'Laptops'),
    (SELECT id FROM class WHERE class_name = 'Mid tier'),
    79999.00, 20, '../assets/PC/macbook-pro14.png', 512, 16
WHERE EXISTS (SELECT 1 FROM category WHERE category_name = 'Laptops')
AND EXISTS (SELECT 1 FROM class WHERE class_name = 'Mid tier');

INSERT INTO products (name, product_description, category_id, class_id, price, stock, img_url, ROM, RAM)
SELECT 
    'M-Book Pro 16',
    'M3 Max Chip<br>22hr Battery Life<br>Up to 128GB Unified Memory<br>8K Video Editing',
    (SELECT id FROM category WHERE category_name = 'Laptops'),
    (SELECT id FROM class WHERE class_name = 'Flag ship'),
    124999.00, 10, '../assets/PC/macbook-pro16.png', 2000, 64
WHERE EXISTS (SELECT 1 FROM category WHERE category_name = 'Laptops')
AND EXISTS (SELECT 1 FROM class WHERE class_name = 'Flag ship');

INSERT INTO products (name, product_description, category_id, class_id, price, stock, img_url, ROM, RAM)
SELECT 
    'M-Station Mini',
    'M2 or M2 Pro Chip<br>Compact Design<br>Wi-Fi 6E<br>Thunderbolt 4',
    (SELECT id FROM category WHERE category_name = 'Desktops'),
    (SELECT id FROM class WHERE class_name = 'Entry level'),
    29999.00, 25, '../assets/PC/mac-mini.png', 256, 8
WHERE EXISTS (SELECT 1 FROM category WHERE category_name = 'Desktops')
AND EXISTS (SELECT 1 FROM class WHERE class_name = 'Entry level');

INSERT INTO products (name, product_description, category_id, class_id, price, stock, img_url, ROM, RAM)
SELECT 
    'M-Station Studio',
    'M2 Max or Ultra<br>Whisper Quiet<br>Up to 192GB Memory<br>6 Thunderbolt Ports',
    (SELECT id FROM category WHERE category_name = 'Desktops'),
    (SELECT id FROM class WHERE class_name = 'Mid tier'),
    99999.00, 10, '../assets/PC/mac-studio.png', 2000, 64
WHERE EXISTS (SELECT 1 FROM category WHERE category_name = 'Desktops')
AND EXISTS (SELECT 1 FROM class WHERE class_name = 'Mid tier');

INSERT INTO products (name, product_description, category_id, class_id, price, stock, img_url, ROM, RAM)
SELECT 
    'M-Station Pro',
    'M2 Ultra Chip<br>PCIe Expansion<br>192GB Unified Memory<br>Rack Mount Available',
    (SELECT id FROM category WHERE category_name = 'Desktops'),
    (SELECT id FROM class WHERE class_name = 'Flag ship'),
    349999.00, 5, '../assets/PC/mac-pro.png', 8000, 192
WHERE EXISTS (SELECT 1 FROM category WHERE category_name = 'Desktops')
AND EXISTS (SELECT 1 FROM class WHERE class_name = 'Flag ship');

```
--use this to reset the auto id
```php
ALTER TABLE class AUTO_INCREMENT = 0;
ALTER TABLE products AUTO_INCREMENT = 0;
ALTER TABLE category AUTO_INCREMENT = 0;
```