<?php

class Products extends Database //this is a product, which can be used by both admin and user, depending on their status
{

    
    public function getProductsbyCategory($categoryID, $sort_by = null, $order = "ASC")
    {
        try
        {               
            $sql = "SELECT * FROM products WHERE category_id = :categoryID"; //default sql query

            if($sort_by)
            {
                $sql .= " ORDER BY " . $sort_by . " " . $order;
            }
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