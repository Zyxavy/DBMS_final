<?php
/**
 * Database.php
 * Handles the central PDO connection for the application.
 * * !!! CRITICAL: YOU MUST UPDATE THE CREDENTIALS BELOW !!!
 * The error you reported (Access denied) is caused by incorrect values here.
 */
class Database
{
    // --- IMPORTANT: FILL IN YOUR DATABASE CREDENTIALS BELOW ---
    private $host = "localhost"; 
    private $dbName = "ecommerce_db"; // <-- CHANGE THIS (e.g., 'manzanas_db')
    private $user = "root";      // <-- CHANGE THIS (e.g., 'root' or 'manzanas_user')
    private $password = "";  // <-- CHANGE THIS (e.g., 's3cur3p@ss')
    
    /**
     * Establishes and returns a PDO database connection.
     * @return PDO|null
     */
    protected function connect()
    {
        try {
            // Data Source Name (DSN) for MySQL
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName . ';charset=utf8mb4';
            
            // Set options for error handling and character set
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            // Create the PDO connection object
            $pdo = new PDO($dsn, $this->user, $this->password, $options);
            return $pdo;

        } catch (PDOException $e) {
            // Log the error for server-side troubleshooting
            error_log("Database Connection Error: " . $e->getMessage());
            
            // Display a critical error message to the user
            die("<h1>Connection Failed!</h1><p>The application could not connect to the database. Please verify your credentials in <code>config/Database.php</code>. Error: " . htmlspecialchars($e->getMessage()) . "</p>");
            
            // Return null if connection fails
            return null;
        }
    }
}