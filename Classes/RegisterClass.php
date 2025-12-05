<?php


class Register extends Database
{
    private $userName;
    private $password;
    private $email;

    //this is a constructor
    public function __construct($password, $userName, $email)
    {
        $this->password = $password;
        $this->userName = $userName;
        $this->email = $email;
    }

    public function registerUser()
    {
        #perform a check
        if($this->isEmptyFields())
        {
            header("Location: /index.php");
            echo "EMPTY FIELDS";
            die();
        }

        try
        {
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

            #create a query and then bind the values
            $sql = "INSERT INTO users (password_hash, username, email) VALUES(:password, :name, :email);";
            $stmt = parent::connect()->prepare($sql);

            $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindValue(':name', $this->userName, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->execute();

            echo "executed SQL";
            return true;
        }
        catch(PDOException $e)
        {
            echo "ERROR " . $e->getMessage();
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
