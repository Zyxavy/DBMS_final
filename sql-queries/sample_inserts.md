
```php
insert into class(class_name) VALUES ("A series");
insert into class(class_name) VALUES ("Galaxy S series");

insert into category(category_name) VALUES ("mobile phone");

insert into products(category_id, class_id,name,
                     price, RAM, ROM, stock) VALUES
                     (1,1,"Manzanas 16", 89999.00, 8, 256, 5);

insert into products(category_id, class_id,name,
                     price, RAM, ROM, stock) VALUES
                     (1,1,"Manzanas E", 15999.00, 8, 256, 5);



INSERT INTO category (id, category_name) VALUES
(1, 'Mobile phones'),
(2, 'Laptops'),
(3, 'System unit'),
(4, 'Input devices'),
(5, 'Output devices');

INSERT INTO class (id, class_name) VALUES
(1, 'Premium'),
(2, 'A series'),
(3, 'E series'),
(4, 'Day series'),
(5, 'Sweet Performance'),
(6, 'I-need'),
(7, 'Superman 2025 limited edition');

INSERT INTO products (product_id, name, category_id, class_id, price, stock, ROM, RAM) VALUES
(1, 'Manzanas 16', 1, 1, 80000.00, 5, 512, 12),
(2, 'Manzanas power', 3, 5, 75000.00, 5, 256, 16),
(3, 'Manzanas E', 1, 3, 4555.00, 10, 64, 6),
(4, 'Manzanas power', 3, 6, 79999.00, 5, 512, 16);


```


--use this to reset the auto id
```php
ALTER TABLE class AUTO_INCREMENT = 0;
ALTER TABLE products AUTO_INCREMENT = 0;
ALTER TABLE category AUTO_INCREMENT = 0;
```