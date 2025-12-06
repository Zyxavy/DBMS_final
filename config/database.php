<?php

class Database
{
    private $host;
    private $dbName = "ecommerce_db";
    private $dbUsername;
    private $dbPassword;
    
    public function __construct()
    {
        if ($this->isDocker()) 
        {
            $this->host = "db"; 
            $this->dbUsername = "admin";
            $this->dbPassword = "1010";
        } 
        else 
        {
            $this->host = "localhost";
            $this->dbUsername = "root";
            $this->dbPassword = "";
        }
    }
    
    private function isDocker()
    {
        //Check if db hostname resolves to a valid IP
        $ip = @gethostbyname('db');
        return $ip !== 'db' && filter_var($ip, FILTER_VALIDATE_IP);
    }
    
    protected function connect()
    {
        try 
        {
            $pdo = new PDO("mysql:host={$this->host};port=3306;dbname={$this->dbName};charset=utf8mb4",$this->dbUsername,$this->dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } 
        catch (PDOException $e) 
        {
            die("Connection Failed! " . $e->getMessage());
        }
    }
}