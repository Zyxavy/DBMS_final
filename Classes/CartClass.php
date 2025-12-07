<?php

class Cart extends Database
{
    public function addToCart($userId, $productId, $quantity)
    {
        
        
        if(!$this->isInStock($productId, $quantity))
        {
            return false;
        }

        if($this->isInCart($userId, $productId))
        {
            try{
                $sql = "UPDATE `cart` SET `quantity`= quantity + :Q WHERE product_Id = :product_ID AND user_id = :user_ID;";
                $stmt = parent::connect()->prepare($sql);

                $stmt->bindParam(':Q', $quantity);
                $stmt->bindParam(':product_ID', $productId);
                $stmt->bindParam(':user_ID', $userId);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo "ERROR " . $e->getMessage();
                return null;
            }
        }
        else
        {
            try{
                $sql = "INSERT INTO `cart`(`user_id`, `product_id`, `quantity`) VALUES (:user_ID, :product_ID, :Q);";
                $stmt = parent::connect()->prepare($sql);

                $stmt->bindParam(':user_ID', $userId);
                $stmt->bindParam(':product_ID', $productId);
                $stmt->bindParam(':Q', $quantity);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo "ERROR " . $e->getMessage();
                return null;
            }
        }
        return false;
    }

    private function isInStock($productId, $quantity)
    {
        try
        {               
            $sql = "SELECT `stock` FROM `products` WHERE product_id = :product_ID;";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':product_ID', $productId);
            $stmt->execute();
            
            $inStock = $stmt->fetch(PDO::FETCH_ASSOC);
            return $inStock['stock'] >= $quantity ? true : false;
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
    }

    private function isInCart($userId, $productId)
    {
        try
        {               
            $sql = "SELECT `product_id` FROM `cart` WHERE product_id = :product_ID AND user_id = :user_ID;";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':product_ID', $productId);
            $stmt->bindParam(':user_ID', $userId);
            $stmt->execute();
            
            if($stmt->fetch(PDO::FETCH_ASSOC))
            {
                return true;
            }
 
            return false;
            
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
    }
}