<?php
namespace TechMarket\Lib;

use PDO;
use PDOException;
class ConnectDB
{
    private $host;
    private $db_name;
    private $user_name;
    private $password;
    public function __construct($host, $db_name, $user_name, $password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->user_name = $user_name;
        $this->password = $password;
    }
    public function connect()
    {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->db_name";
            $pdo = new PDO($dsn, $this->user_name, $this->password);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }

}