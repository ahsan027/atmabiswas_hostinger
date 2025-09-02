<?php
// Database connection class for ATMABISWAS
// Updated to use Singleton pattern for single database connection

class Db
{
    private static $instance = null;
    private $hostname = "localhost";
    private $user = "root";
    private $pswd = "";
    private $dbname = "atmabiswas";
    private $pdo;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $this->initializeConnection();
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing of the instance
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    // Get the single instance of the database connection
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Initialize the database connection
    private function initializeConnection()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host=$this->hostname;dbname=$this->dbname;charset=utf8mb4",
                $this->user,
                $this->pswd,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed. Please try again later.");
        }
    }

    // Get the PDO connection
    public function getConnection()
    {
        return $this->pdo;
    }

    // Legacy method for backward compatibility
    public function connect()
    {
        return $this->pdo;
    }

    // Close the database connection
    public function closeConnection()
    {
        $this->pdo = null;
        self::$instance = null;
    }

    // Get connection status
    public function isConnected()
    {
        return $this->pdo !== null;
    }
}

// Global function for easy access to database connection
function getDB()
{
    return Db::getInstance()->getConnection();
}
