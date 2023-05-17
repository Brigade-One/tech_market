<?php
namespace TechMarket\Lib;

use TechMarket\Lib\ConnectDB;
use PDO;

class DBController
{
    private static $pdo;
    private $connectDB;

    public function __construct($connectDB)
    {
        $this->connectDB = $connectDB;
    }

    private static function connect()
    {
        $connectDB = new ConnectDB('localhost', 'tech_market_db', 'root', '');
        if (!isset(self::$pdo)) {
            self::$pdo = $connectDB->connect();
        }
        return self::$pdo;
    }

    public function getItemsByQuery($request)
    {
        $pdo = self::connect();
        $stmt = $pdo->query($request);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getItemInstanceByID($id)
    {
        $pdo = self::connect();
        $stmt = $pdo->query("SELECT i.*, v.v_name, c.c_name FROM items i JOIN vendors v ON i.FID_Vendor = v.ID_Vendors JOIN
category c ON i.FID_Category = c.ID_Category WHERE i.id = $id");
        if (!$stmt) {
            echo "Error executing query: " . $pdo->errorInfo()[2];
            return false;
        }
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header("Content-Type: text/html; charset=utf-8");
        echo json_encode($result[0]);
        return $result[0];
    }
    function createSqlQueryFromSearchRequest()
    {
        $query = $_GET['query'];
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $quality = isset($_GET['quality']) ? $_GET['quality'] : null;
        $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : null;
        $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : null;

        // Build the SQL query based on the search parameters
        if ($query == '') {
            $query = '%';
        } else {
            $query = "%$query%";
        }
        $sql = "SELECT i.*, v.v_name, c.c_name FROM items i JOIN vendors v ON i.FID_Vendor = v.ID_Vendors JOIN
        category c ON i.FID_Category = c.ID_Category WHERE name LIKE '%$query%'";
        if ($category) {
            $categories = explode(',', $category);
            $sql .= " AND c_name IN ('" . implode("', '", $categories) . "')";
        }
        if ($quality) {
            $qualityArr = explode(',', $quality);
            $qualityInClause = implode(',', array_map(fn($q) => "'$q'", $qualityArr));
            $sql .= " AND quality IN ($qualityInClause)";
        }
        if ($minPrice) {
            $sql .= " AND price >= $minPrice";
        }
        if ($maxPrice) {
            $sql .= " AND price <= $maxPrice";
        }
        return $sql;
    }
}