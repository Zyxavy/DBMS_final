<?php

class Address extends Database
{
    public function getUserAddresses($userId)
    {
        try 
        {
            $sql = "SELECT * FROM address WHERE user_id = :userId ORDER BY is_default DESC;";
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) 
        {
            error_log("address fetch error: " . $e->getMessage());
            return [];
        }
    }

    public function getDefaultAddress($userId)
    {
        try 
        {
            $sql = "SELECT * FROM address WHERE user_id = :userId AND is_default = 1 LIMIT 1;";
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        }
        catch(PDOException $e) {
            error_log("address fetch error: " . $e->getMessage());
            return [];
        }
    }

    public function unsetDefault($userId)
    {
        try 
        {
            $sql = "UPDATE address SET is_default = 0 WHERE user_id = :userId;";
            $stmt = parent::connect()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            return $stmt->execute();
        }
        catch(PDOException $e) {
            error_log("unset default error: " . $e->getMessage());
            return false;
        }
    }

    public function addAddress($userId, $data)
    {
        try 
        {
            if (!empty($data['is_default'])) 
                {
                $this->unsetDefault($userId);
            }

            $sql = "INSERT INTO address (user_id, address_type, street_address, city, province, postal_code, unit_num, is_default)
                    VALUES (:userId, :addressType, :street_address, :city, :province, :postal_code, :unit_num, :is_default);";

            $stmt = parent::connect()->prepare($sql);

            $stmt->execute([
                ':userId' => $userId,
                ':addressType' => $data['address_type'],
                ':street_address' => $data['street_address'],
                ':city' => $data['city'],
                ':province' => $data['province'],
                ':postal_code' => $data['postal_code'],
                ':unit_num' => $data['unit_num'], 
                ':is_default' => $data['is_default']
            ]);

            return true;
        }
        catch(PDOException $e) 
        {
            error_log("addAddress error: " . $e->getMessage());
            return false;
        }
    }

    public function updateAddress($addressId, $data)
    {
        try 
        {
            if (!empty($data['is_default'])) {
                $this->unsetDefault($data['user_id']);
            }

            $sql = "UPDATE address 
                    SET address_type = :address_type,
                        street_address = :street_address,
                        city = :city,
                        province = :province,
                        postal_code = :postal_code,
                        unit_num = :unit_num,
                        is_default = :is_default
                    WHERE address_id = :address_id;";

            $stmt = parent::connect()->prepare($sql);

            return $stmt->execute([
                ':address_type' => $data['address_type'],
                ':street_address' => $data['street_address'],
                ':city' => $data['city'],
                ':province' => $data['province'],
                ':postal_code' => $data['postal_code'],
                ':unit_num' => $data['unit_num'],
                ':is_default' => $data['is_default'],
                ':address_id' => $addressId
            ]);
        }
        catch(PDOException $e) 
        {
            error_log("updateAddress error: " . $e->getMessage());
            return false;
        }
    }
}
