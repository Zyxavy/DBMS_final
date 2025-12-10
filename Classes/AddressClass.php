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
        $addressType = $addressData['address_type'];
        $streetAddress = $addressData['street_address'];
        $city = $addressData['city'];
        $province = $addressData['province'];
        $postalCode = $addressData['postal_code'];
        $unitNum = $addressData['unitNum'];

        try
        {               
            $sql = "INSERT INTO address (user_id, address_type, street_address, city, province, postal_code, unit_num) 
            VALUES(:userId, :addressType, :streetAddress, :city, :province, :postalCode, :unitNum);";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':addressType', $addressType, PDO::PARAM_STR);
            $stmt->bindValue(':streetAddress', $streetAddress, PDO::PARAM_STR);
            $stmt->bindValue(':city', $city, PDO::PARAM_STR);
            $stmt->bindValue(':province', $province, PDO::PARAM_STR);
            $stmt->bindValue(':postalCode', $postalCode, PDO::PARAM_STR);
            $stmt->bindValue(':unitNum', $unitNum, PDO::PARAM_STR);;
            $stmt->execute();
            
            return true;
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
    }
}