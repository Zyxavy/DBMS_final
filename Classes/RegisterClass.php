<?php

class Register extends Database
{
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $userName;
    private $password;

    //this is a constructor
    public function __construct($firstName, $lastName, $email, $phone, $userName, $password)
    {
        parent::__construct();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->userName = $userName;
        $this->password = $password;
    }


    public function registerUser()
    {
        if($this->isEmptyFields())
        {
            header("Location: /index.php");
            die();
        }

        try
        {
            $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

            //create a query and then bind the values
            $sql = "INSERT INTO users (password_hash, username, first_name, last_name, email, phone) VALUES(:password, :username, :firstName, :lastName, :email, :phone);";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindValue(':username', $this->userName, PDO::PARAM_STR);
            $stmt->bindValue(':firstName', $this->firstName, PDO::PARAM_STR);
            $stmt->bindValue(':lastName', $this->lastName, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':phone', $this->phone, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        }
        catch(PDOException $e)
        {
            error_log("ERROR " . $e->getMessage());
            return null;
        }
        
    }

    public function createAddress($userName, $type, $address, $city, $province, $postalCode, $unitNum)
    {
        try
        {
            $userId = $this->getUserID($userName);
            //create a query and then bind the values
            $sql = "INSERT INTO address (user_id, address_type, street_address, city, province, postal_code, unit_num) VALUES(:userID, :type, :address, :city, :province, :postalCode, :unitNum)";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindValue(':userID', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':address', $address, PDO::PARAM_STR);
            $stmt->bindValue(':city', $city, PDO::PARAM_STR);
            $stmt->bindValue(':province', $province, PDO::PARAM_STR);
            $stmt->bindValue(':postalCode', $postalCode, PDO::PARAM_STR);
            $stmt->bindValue(':unitNum', $unitNum, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        }
        catch(PDOException $e)
        {
            error_log("ERROR " . $e->getMessage());
            return null;
        }
    }

    private function getUserID($userName)
    {
        try
        {
            //create a query and then bind the values
            $sql = "SELECT user_id FROM users WHERE username = :userName";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindValue(':userName', $this->userName, PDO::PARAM_STR);
            $stmt->execute();

            $userId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $userId['user_id'] ?? false;
        }
        catch(PDOException $e)
        {
            error_log("ERROR " . $e->getMessage());
            return null;
        }
    }

    private function isEmptyFields()
    {
        if(isset($this->password) && isset($this->userName) && isset($this->email))
        {
            return false;
        }
        return true;
    }

    //TO-DO: methods for validation and security checks
   
}
