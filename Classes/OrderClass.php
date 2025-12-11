<?php
class Order extends Database
{

    public function createOrder($userId, $addressId, $totalAmount, $paymentMethod)
    {
        try
        {  
            $conn = parent::connect();             
            $sql = "INSERT INTO orders (user_id, address_id, total_amount, payment_method) VALUES (:userId, :addressId, :totalAmount, :paymentMethod);";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':addressId', $addressId, PDO::PARAM_INT);
            $stmt->bindValue(':totalAmount', $totalAmount, PDO::PARAM_STR);
            $stmt->bindValue(':paymentMethod', $paymentMethod, PDO::PARAM_STR);

            $stmt->execute();
            
            return $conn->lastInsertId();
        }
        catch(PDOException $e)
        {
            error_log("error: " . $e->getMessage());
            return [];
        }
    }

    public function addOrderItems($orderId, $cartItems)
    {
        try
        {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal_price) VALUES (:order_id, :product_id, :quantity, :unit_price, :subtotal_price)";
            $stmt = parent::connect()->prepare($sql);

            foreach($cartItems as $item)
            {
                $subtotal = $item['price'] * $item['quantity'];

                $stmt->bindValue(':order_id', $orderId, PDO::PARAM_INT);
                $stmt->bindValue(':product_id', $item['product_id'], PDO::PARAM_INT);
                $stmt->bindValue(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmt->bindValue(':unit_price', $item['price'], PDO::PARAM_INT);
                $stmt->bindValue(':subtotal_price', $subtotal, PDO::PARAM_INT);
                $stmt->execute();
            }
            return true;
        }
        catch(PDOException $e)
        {
            error_log("Order item insert error: " . $e->getMessage());
            return false;
        }
    }

    public function getUserOrders($userId)
    {
        try 
        {
            $sql = "SELECT order_id, order_date, total_amount, order_status, payment_method, payment_status
                    FROM orders WHERE user_id = :userId ORDER BY order_date DESC;";

            $stmt = parent::connect()->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) 
        {
            error_log("order fetch error: " . $e->getMessage());
            return [];
        }
    }

    public function getOrderItems($orderId)
    {
        try 
        {
            $sql = "SELECT oi.quantity, oi.unit_price, oi.subtotal_price, p.name AS product_name,p.img_url AS product_image
                    FROM order_items oi INNER JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = :orderId";

            $stmt = parent::connect()->prepare($sql);
            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } 
        catch(PDOException $e) 
        {
            error_log("order items fetch error: " . $e->getMessage());
            return [];
        }
    }




}