
<?php // Mahimo kita hin database connetion class

class Database
{
    //since local pamanla
    private $host = "localhost";
    private $dbName = "proto_ecommerce_db";
    private $dbUsername = "root";
    private $dbPassword = "";

    //this functions returns an object 
    protected function connect()
    {
        try
        {
            $pdo = new PDO("mysql:host=". $this->host . ";dbname=" . $this->dbName, $this->dbUsername, $this->dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        }
        catch(PDOException $e)
        {
            die("Connection Failed!". $e->getMessage());
        }
    }



}