<?php

class Products extends Database //this is a product, which can be used by both admin and user, depending on their status
{

    
    public function getProductsbyCategory($categoryID)
    {
        try
        {
            $sql = "SELECT * FROM products WHERE category_id = :categoryID";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':categoryID', $categoryID);
            $stmt->execute();
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
    }
}