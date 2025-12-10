<?php

class Address extends Database
{
    public function getUserAddresses($userId)
    {
        try
        {               
            $sql = "SELECT * FROM products WHERE user_id = :userId;";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            
            $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $addresses ?? false;
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
    }

    public function getDefaultAddress($userId)
    {
        try
        {               
            $sql = "SELECT * FROM products WHERE user_id = :userId AND is_default = 1;";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            
            $default = $stmt->fetch(PDO::FETCH_ASSOC);
            return $default ?? false;
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
    }

    public function addAddress($userId, $addressData)
    {
        return 0;
    }
}