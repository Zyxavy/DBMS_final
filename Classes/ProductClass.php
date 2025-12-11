<?php

class Products extends Database //this is a product, which can be used by both admin and user, depending on their status
{

    
    public function getProductsbyCategory($categoryID, $sort_by = null, $order = "ASC")
    {
        try
        {               
            $sql = "SELECT p.*, cl.class_name FROM products p JOIN class cl ON p.class_id = cl.id WHERE p.category_id = :categoryID"; //default sql query

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

    public function getAllProducts($sort_by = null, $order = "ASC")
    {
        try
        {               
            $sql = "SELECT p.*, c.category_name, cl.class_name 
                    FROM products p 
                    JOIN category c ON p.category_id = c.id
                    JOIN class cl ON p.class_id = cl.id";
            
            if($sort_by)
            {
                $sql .= " ORDER BY " . $sort_by . " " . $order;
            }
            $stmt = parent::connect()->prepare($sql);
            $stmt->execute();
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }

    public function getProductsByName($productNames = [])
    {
        try
        {               
            if(empty($productNames)) {
                return $this->getAllProducts();
            }
            
            $placeholders = implode(',', array_fill(0, count($productNames), '?'));
            
            $sql = "SELECT p.*, c.category_name, cl.class_name 
                    FROM products p 
                    JOIN category c ON p.category_id = c.id
                    JOIN class cl ON p.class_id = cl.id
                    WHERE p.name IN ($placeholders)";
            
            $stmt = parent::connect()->prepare($sql);
            $stmt->execute($productNames);
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }

    public function reduceProductStock($productId, $quantity)
    {
        try {
        $sql = "UPDATE products SET stock = stock - :quantity WHERE product_id = :productId";

        $stmt = parent::connect()->prepare($sql);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
        
        } catch (PDOException $e) {
            error_log("Stock reduction error: " . $e->getMessage());
            return false;
        }
    }
}