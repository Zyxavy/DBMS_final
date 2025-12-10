<?

class Order extends Database
{
    public function createOrder($userId, $addressId, $totalAmount, $paymentMethod)
    {
        try
        {               
            $sql = "INSERT INTO orders (user_id, address_id, total_amount, payment_method) VALUES (:userID, :addressID, :totalAmount, :paymentMethod);";
            $stmt = parent::connect()->prepare($sql);
            
            $stmt->bindValue(':userID', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':addressID', $addressId, PDO::PARAM_INT);
            $stmt->bindValue(':totalAmount', $totalAmount, PDO::PARAM_INT);
            $stmt->bindValue(':paymentMethod', $paymentMethod, PDO::PARAM_STR);

            $stmt->execute();
            
            return true;
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
    }

    public function addOrderItems($orderId, $cartItems)
    {
        return 0;
    }
}