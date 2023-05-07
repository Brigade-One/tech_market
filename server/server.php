<?php
require_once 'lib/http_router.php';
header('Access-Control-Allow-Origin: *');
// Server parameters.
$host = 'techmarket';
$port = 80;
$docroot = __DIR__;

startServer($host, $port, $docroot);

// Helps to avoid if statements.
$router = new HttpRouter();

$router->addRoute('GET', '/get_all_items', function () {
    return _getAllItemsFromDB('SELECT * FROM items');
});
$router->addRoute('POST', '/sign_up', function () {
    return _signUp('data/users.txt');
});
$router->addRoute('POST', '/sign_in', function () {
    return _signIn('data/users.txt');
});

$itemId = $_GET['id'];

$router->addRoute('GET', '/product', function () {
    return _getItemFromDBById($_GET['id']);
});

// HTTP method and URI of the request.
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];

// Route the request.
$router->route($method, $path);

function _signUp($filename)
{
    require_once 'lib/sign_up.php';
    $signup = new SignUp($filename);
    // Client data in JSON format
    $jsonData = file_get_contents('php://input');
    // Process the data
    $result = $signup->processSignUpData($jsonData);
    // Send the response in JSON format
    _sendAuthRequestResponse($result);
}
function _signIn($filename)
{
    require_once 'lib/sign_in.php';
    $logHandler = new LogHandler();
    $signin = new SignIn($filename);
    $jsonData = file_get_contents('php://input');
    $result = $signin->processSignInData($jsonData);
    _sendAuthRequestResponse($result);
    $logHandler->logEvent($result['message']);
}

function _getAllItemsFromDB($request)
{
    require_once 'lib/connect_db.php';
    $db = new ConnectDB('techmarket', 'tech_market_db', 'root', '');
    $pdo = $db->connect();
    $stmt = $pdo->query($request);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}
function _getItemFromDBById($id)
{
    require_once 'lib/connect_db.php';
    $db = new ConnectDB('techmarket', 'tech_market_db', 'root', '');
    $pdo = $db->connect();
    $stmt = $pdo->query("SELECT i.*, v.v_name, c.c_name FROM items i JOIN vendors v ON i.FID_Vendor = v.ID_Vendors JOIN category c ON i.FID_Category = c.ID_Category WHERE i.id = $id");
    if (!$stmt) {
        echo "Error executing query: " . $pdo->errorInfo()[2];
        return false;
    }
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: text/html; charset=utf-8");
    echo _generateInfoBlock(json_encode($result[0]));
}


function _generateInfoBlock($item)
{
    $item = json_decode($item, true);
    var_dump($item);
    $main = ' 
        <div id="product-details">
            <h1>' . $item['name'] . '</h1>
            <p>' . $item['description'] . '</p>
            <p>Quantity in stock: ' . $item['quantity'] . '</p>
            <p> Price: ' . $item['price'] . '</p>   
            <p>Vendor: ' . $item['v_name'] . '</p>
            <p>Category: ' . $item['c_name'] . '</p>
            <p> Quality: ' . $item['quality'] . "/" . '5' . '</p>
        </div>
    ';
    return $main;
}


function _sendAuthRequestResponse($result)
{
    require_once 'lib/log_handler.php';
    $logHandler = new LogHandler();

    header('Content-Type: application/json');
    if ($result['success'] === true) {
        echo json_encode(['success' => true, 'message' => $result['message'], 'user' => $result['user']]);
    } else {
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
    $logHandler->logEvent($result['message']);
}

function startServer($host, $port, $docroot)
{
    $cmd = sprintf('php -S %s:%d -t %s', $host, $port, $docroot);
    shell_exec($cmd);
}

?>