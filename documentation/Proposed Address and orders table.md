
Para **address Table**: similar to shopee wher users can have many address, one for home or another one for work

```php
address_id: 1, user_id: 1, address_type: home, street_address: "Blk 2, Lot 5 Burgos St", city: "Tacloban City", province: "Leyte", postal_code: 6500, is_default: 1

address_id: 2, user_id: 1, address_type: work, street_address: "2nd Floor, Robinsons North", city: "Tacloban City", province: "Leyte", postal_code: 6500, is_default: 0

address_id: 3, user_id: 2, address_type: home, street_address: "Lolita homes, Brgy Guindapunan", city: "Palo", province: "Leyte", postal_code: 6501, is_default: 1

address_id: 4, user_id: 3, address_type: home, street_address: "Brgy. Pawing", city: "Palo", province: "Leyte", postal_code: 6501, is_default: 1
```

and for **orders table**, user_id is a foreign key to the users table, and address id will link to the address table.

```php
order_id: 1, user_id: 1, address_id: 1, order_date: 2025-01-20 10:23:45, total_amount: 56.00, status: pending,  payment_method: gcash, payment_status: unpaid

order_id: 2, user_id: 1, address_id: 2, order_date: 2025-01-22 14:10:21, total_amount: 3452.00, status: shipped, payment_method: credit_card, payment_status: paid

order_id: 3, user_id: 2, address_id: 3, order_date: 2025-02-02 08:01:10, total_amount: 555555.00, status: delivered,  payment_method: cash_on_delivery, payment_status: paid

order_id: 4, user_id: 3, address_id: 4, order_date: 2025-02-05 16:44:12, total_amount: 2315123.00, status: cancelled,  payment_method: paymaya, payment_status: refunded
```

and **items table** that would hold the items: order_id will link to the orders table, and product_id will link to the product table.

```php
order_item_id: 1, order_id: 1, product_id: 3, quantity: 2, unit_price: 4.00, subtotal_price: 8.00

order_item_id: 2, order_id: 1, product_id: 1, quantity: 1, unit_price: 555,523.2, subtotal_price: 555,523.2

order_item_id: 3, order_id: 2, product_id: 4, quantity: 1, unit_price: 79,999.00, subtotal_price: 79,999.00

order_item_id: 4, order_id: 3, product_id: 2, quantity: 1, unit_price: 4,500.00, subtotal_price: 4,500.00


```