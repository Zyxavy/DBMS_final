<?php

class Login extends Database
{
    private $userName;
    private $password;

    //this is a constructor
    public function __construct($userName, $password)
    {
        parent::__construct();
        $this->userName = $userName;
        $this->password = $password;
    }

    public function authenticateUser()
    {
        try
        {
            //select user from db
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':username', $this->userName);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            //check if the user is in the db
            if($user == null)
            {
                return false;
            }

            //verify the password hash
            if(password_verify($this->password,$user['password_hash']))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
        
    }

    public function getUserStatus()
    {
        try
        {
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindParam(':username', $this->userName);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;

        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
            return null;
        }
    }


}
