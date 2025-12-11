<?php

class Profile extends Database
{
    private $userId;

    public function __construct($session_user_id)
    {
        parent::__construct();
        $this->userId = $session_user_id;
    }

    public function getUserDetails()
    {
        try
        {
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':username', $this->userId);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);


            if($user == null)
            {
                return false;
            }
            return $user;
            
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }

    public function setUserName($newUserName)
    {
        try
        {
            $sql = "UPDATE users SET  username = :newUserName WHERE user_id = :userID";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':username', $newUserName);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();
            
            return $stmt->execute() ?? false;
            
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }

     public function setFirstName($newFirstName)
    {
        try
        {
            $sql = "UPDATE users SET  first_name = :newFirstName WHERE user_id = :userID";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':first_name', $newFirstName);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();
            
            return $stmt->execute() ?? false;
            
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }

     public function setLastName($newLastName)
    {
        try
        {
            $sql = "UPDATE users SET  last_name = :newLastName WHERE user_id = :userID";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':last_name', $newLastName);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();
            
            return $stmt->execute() ?? false;
            
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }

    public function setEmail($newEmail)
    {
        try
        {
            $sql = "UPDATE users SET  email = :newEmail WHERE user_id = :userID";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':email', $newEmail);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();
            
            return $stmt->execute() ?? false;
            
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }

    public function setPhoneNum($newPhoneNum)
    {
        try
        {
            $sql = "UPDATE users SET  phone = :newPhoneNum WHERE user_id = :userID";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':phone', $newPhoneNum);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();
            
            return $stmt->execute() ?? false;
            
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }

    public function setPassword($newPassword)
    {
        $hashpassword = password_hash($newPassword, PASSWORD_BCRYPT);

        try
        {
            $sql = "UPDATE users SET  password_hash = :newPassword WHERE user_id = :userID";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':password_hash', $$hashpassword);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();
            
            return $stmt->execute() ?? false;
            
        }
        catch(PDOException $e)
        {
            error_log("Cart error: " . $e->getMessage());
            return [];
        }
    }
}