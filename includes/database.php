<?php
include('credentials.php');

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host = constant('host');
        $dbname = constant('dbname');
        $username = constant('username');
        $password = constant('password');
        $port = constant('port');

        try {
            $this->connection = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}
?>