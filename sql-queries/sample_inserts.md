
insert into class(class_name) VALUES ("A series");
insert into class(class_name) VALUES ("Galaxy S series");

insert into category(category_name) VALUES ("mobile phone");

insert into products(category_id, class_id,name,
                     price, RAM, ROM, stock) VALUES
                     (1,1,"Manzanas 16", 89999.00, 8, 256, 5);
insert into products(category_id, class_id,name,
                     price, RAM, ROM, stock) VALUES
                     (1,1,"Manzanas E", 15999.00, 8, 256, 5);


--use this to reset the auto id
```
ALTER TABLE class AUTO_INCREMENT = 0;
ALTER TABLE products AUTO_INCREMENT = 0;
ALTER TABLE category AUTO_INCREMENT = 0;
```