
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



INSERT INTO category (category_name) VALUES
('Mobile phones'),
('Laptops'),
('System unit'),
('Input devices'),
('Output devices');

INSERT INTO class (class_name) VALUES
('Premium'),
('A series'),
('E series'),
('Day series'),
('Sweet Performance'),
('I-need'),
('Superman 2025 limited edition');

INSERT INTO products (name, category_id, class_id, price, stock, ROM, RAM) VALUES
('Manzanas 16', 1, 1, 80000.00, 5, 512, 12),
('Manzanas power', 3, 5, 75000.00, 5, 256, 16),
('Manzanas E', 1, 3, 4555.00, 10, 64, 6),
('Manzanas power', 3, 6, 79999.00, 5, 512, 16);


```


--use this to reset the auto id
```php
ALTER TABLE class AUTO_INCREMENT = 0;
ALTER TABLE products AUTO_INCREMENT = 0;
ALTER TABLE category AUTO_INCREMENT = 0;
```