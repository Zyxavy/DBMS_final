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
}