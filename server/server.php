<?php
require_once 'lib/http_router.php';
require_once 'lib/token_manager.php';

header('Access-Control-Allow-Origin: http://techmarkethome/');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Server parameters.
$host = 'techmarket';
$port = 80;
$docroot = __DIR__;
startServer($host, $port, $docroot);

$router = new HttpRouter();
$router->addRoute('POST', '/sign_up', function () {
    return _signUp('data/users.txt');
});
$router->addRoute('POST', '/sign_in', function () {
    return _signIn('data/users.txt');
});
$router->addRoute('GET', '/get_all_items', function () {
    return _getAllItemsFromDB('SELECT * FROM items');
});
$router->addRoute('GET', '/product', function () {
    return _getItemInstanceByID($_GET['id']);
});
$router->addRoute('POST', '/order', function () {
    $token = $_GET['token'];
    if (!_verifyToken($token)) {
        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized (invalid token)';
        return;
    }
    return _order('data/orders.txt', $token);
});


$router->addRoute('POST', '/get_order_history', function () {
    $token = $_GET['token'];
    if (!_verifyToken($token)) {
        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized (invalid token)';
        return;
    }

    return _getOrderHistory('data/orders.txt');
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
    // Get the data from the request body
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
    // Get the data from the request body
    $jsonData = file_get_contents('php://input');
    $result = $signin->processSignInData($jsonData);
    _sendAuthRequestResponse($result);
    $logHandler->logEvent($result['message']);
}
function _verifyToken($token)
{
    $tokenManager = new TokenManager();
    return $tokenManager->verifyToken($token);
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
function _getItemInstanceByID($id)
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
    echo json_encode($result[0]);
}
function _order($filename, $token)
{
    require_once 'lib/order.php';
    require_once 'lib/order_manager.php';
    $order = new OrderManager($filename);
    $jsonData = file_get_contents('php://input');
    $result = $order->writeOrderData($jsonData, $filename);
    _sendAuthRequestResponse($result);
}
function _getOrderHistory($filename)
{
    require_once 'lib/order.php';
    require_once 'lib/order_manager.php';
    $order = new OrderManager($filename);
    $result = $order->readOrderData($filename);
    _sendAuthRequestResponse($result);
}

function _sendAuthRequestResponse($result)
{
    require_once 'lib/log_handler.php';
    $logHandler = new LogHandler();

    header('Content-Type: application/json');
    if ($result['success'] === true) {
        echo json_encode(['success' => true, 'message' => $result['message'], 'token' => $result['token'], 'user' => $result['user']]);
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