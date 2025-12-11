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
            $sql = "SELECT * FROM users WHERE user_id = :user_id";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':user_id', $this->userId);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        }
        catch (PDOException $e) 
        {
            error_log("Profile error: " . $e->getMessage());
            return [];
        }
    }

    public function setFirstName($newFirstName)
    {
        try 
        {
            $sql = "UPDATE users SET first_name = :first_name WHERE user_id = :user_id";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':first_name', $newFirstName);
            $stmt->bindParam(':user_id', $this->userId);

            return $stmt->execute();
        }
        catch (PDOException $e) 
        {
            error_log("Profile error: " . $e->getMessage());
            return false;
        }
    }

    public function setLastName($newLastName)
    {
        try 
        {
            $sql = "UPDATE users SET last_name = :last_name WHERE user_id = :user_id";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':last_name', $newLastName);
            $stmt->bindParam(':user_id', $this->userId);

            return $stmt->execute();
        }
        catch (PDOException $e) 
        {
            error_log("Profile error: " . $e->getMessage());
            return false;
        }
    }

    public function setEmail($newEmail)
    {
        try 
        {
            $sql = "UPDATE users SET email = :email WHERE user_id = :user_id";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':email', $newEmail);
            $stmt->bindParam(':user_id', $this->userId);

            return $stmt->execute();
        }
        catch (PDOException $e) 
        {
            error_log("Profile error: " . $e->getMessage());
            return false;
        }
    }

    public function setPhoneNum($newPhoneNum)
    {
        try
        {
            $sql = "UPDATE users SET phone = :phone WHERE user_id = :user_id";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':phone', $newPhoneNum);
            $stmt->bindParam(':user_id', $this->userId);

            return $stmt->execute();
        }
        catch (PDOException $e) 
        {
            error_log("Profile error: " . $e->getMessage());
            return false;
        }
    }

    public function setPassword($newPassword)
    {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);

        try 
        {
            $sql = "UPDATE users SET password_hash = :password_hash WHERE user_id = :user_id";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':password_hash', $hash);
            $stmt->bindParam(':user_id', $this->userId);

            return $stmt->execute();
        }
        catch (PDOException $e) 
        {
            error_log("Profile error: " . $e->getMessage());
            return false;
        }
    }
}
