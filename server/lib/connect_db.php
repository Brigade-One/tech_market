<?php
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
        $dsn = "mysql:host=$this->host;dbname=$this->db_name";
        $pdo = new PDO($dsn, $this->user_name, $this->password);
        return $pdo;
    }
}