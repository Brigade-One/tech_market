<?php
require_once 'lib/http_router.php';
require_once 'lib/token_manager.php';

header('Access-Control-Allow-Origin: http://techmarkethome');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Server parameters.
$host = 'techmarket';
$port = 80;
$docroot = __DIR__;
startServer($host, $port, $docroot);

$router = new HttpRouter();
$router->addRoute('POST', '/sign_up', function () {
    require_once 'lib/auth_controller.php';
    return AuthController::signUp('data/users.txt');
});
$router->addRoute('POST', '/sign_in', function () {
    require_once 'lib/auth_controller.php';
    return AuthController::signIn('data/users.txt');
});
$router->addRoute('GET', '/get_all_items', function () {
    require_once 'lib/db_controller.php';
    DBController::getAllItemsFromDB('SELECT * FROM items');
});
$router->addRoute('GET', '/product', function () {
    require_once 'lib/db_controller.php';
    DBController::getItemInstanceByID($_GET['id']);
});
$router->addRoute('POST', '/order', function () {
    require_once 'lib/auth_controller.php';
    require_once 'lib/order_controller.php';
    $token = $_GET['token'];

    if (AuthController::verifyToken($token)) {
        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized (invalid token)';
        return;
    }
    return OrderController::order('data/orders.txt', $token);
});


$router->addRoute('POST', '/get_order_history', function () {
    $token = $_GET['token'];
    require_once 'lib/auth_controller.php';
    require_once 'lib/order_controller.php';

    if (AuthController::verifyToken($token)) {
        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized (invalid token)';
        return;
    }
    return OrderController::getOrderHistory('data/orders.txt', $token);
});

// HTTP method and URI of the request.
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];

// Route the request.
$router->route($method, $path);


function startServer($host, $port, $docroot)
{
    $cmd = sprintf('php -S %s:%d -t %s', $host, $port, $docroot);
    shell_exec($cmd);
}

?>