<?php
define('DB_NAME', 'techmarket');
define('DB_TABLE', 'tech_market_db');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

class DBController
{
    private static $pdo;

    private static function connect()
    {
        require_once 'lib/connect_db.php';
        if (!isset(self::$pdo)) {
            self::$pdo = (new ConnectDB(DB_NAME, DB_TABLE, DB_USER, DB_PASSWORD))->connect();
        }
        return self::$pdo;
    }

    public static function getItemsByQuery($request)
    {
        $pdo = self::connect();
        $stmt = $pdo->query($request);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getItemInstanceByID($id)
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
    static function createSqlQueryFromSearchRequest()
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