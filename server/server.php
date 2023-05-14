<?php
require '..\vendor\autoload.php';
require_once 'lib/http_router.php';
require_once 'lib/auth_controller.php';
require_once 'lib/db_controller.php';
require_once 'lib/order_controller.php';
require_once 'lib/message_logger.php';
require_once 'lib/message_queue.php';

header('Access-Control-Allow-Origin: http://techmarkethome');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

use ServerTasks\OrderTask;
use ServerTasks\DatabaseTask;
use ServerTasks\AuthTask;

// Server parameters.
$host = 'techmarket';
$port = 80;
$docroot = __DIR__;
startServer($host, $port, $docroot);

$logger = new MessageLogger('logs/server_tasks_log.txt');
$queue = new MessageQueue();
$router = new HttpRouter();

$router->addRoute('POST', '/sign_up', function () use ($logger, $queue) {
    $logger->log('Client requested sign up');
    $task = new AuthTask('signUp', ['jsonData' => file_get_contents('php://input')]);
    $queue->add($task);
    /* AuthController::signUp(file_get_contents('php://input')); */
});
$router->addRoute('POST', '/sign_in', function () use ($logger, $queue) {
    /* $logger->log('Client requested sign in');
    $task = new AuthTask('signIn', ['jsonData' => file_get_contents('php://input')]);
    $queue->add($task); */
    $result = AuthController::signIn(file_get_contents('php://input'));
    echo json_encode($result);
});
$router->addRoute('GET', '/get_all_items', function () use ($logger, $queue) {
    /*  $logger->log('Client requested all items from DB');
    $task = new DatabaseTask('getItemsByQuery', ["searchQuery" => 'SELECT * FROM items']);
     $queue->add($task); */
    $db = new DBController();
    $result = $db->getItemsByQuery('SELECT * FROM items');
    echo json_encode($result);

});

$router->addRoute('GET', '/search', function () {
    /* $logger->log('Client requested all items from DB');
    $task = new DatabaseTask('getItemsByQuery', ["searchQuery" => $_GET['query']]);
    $queue->add($task); */
    $db = new DBController();
    $query = $_GET['query'];
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $quality = isset($_GET['quality']) ? $_GET['quality'] : null;
    $price = isset($_GET['price']) ? $_GET['price'] : null;

    // Build the SQL query based on the search parameters
    $sql = "SELECT * FROM items WHERE name LIKE '%$query%'";
    if ($category) {
        $categories = explode(',', $category);
        $sql .= " AND category IN ('" . implode("', '", $categories) . "')";
    }
    if ($quality) {
        $qualityArr = explode(',', $quality);
        $qualityInClause = implode(',', array_map(fn($q) => "'$q'", $qualityArr));
        $sql .= " AND quality IN ($qualityInClause)";
    }
    if ($price) {
        $sql .= " AND price <= $price";
    }

    $result = $db->getItemsByQuery($sql);
    echo json_encode($result);
});

$router->addRoute('GET', '/product', function () use ($logger, $queue) {
    /*  $logger->log('Client requested product with ID: ' . $_GET['id']);
     $task = new DatabaseTask('getItemById', ['id' => $_GET['id']]);
     $queue->add($task); */
    $db = new DBController();
    $result = $db->getItemInstanceByID($_GET['id']);
    echo json_encode($result);
});
$router->addRoute('POST', '/order', function () use ($logger, $queue) {
    $token = $_GET['token'];
    if (!AuthController::verifyToken($token)) {
        handleUnauthorizedRequest($logger);
        return;
    }
    /* $task = new OrderTask("order", ['token' => $token, 'jsonData' => file_get_contents('php://input')]);
    $queue->add($task);
    $logger->log('Added write order task to the message queue'); */
    OrderController::writeOrderData($token, file_get_contents('php://input'));
    echo json_encode(['success' => true, 'message' => 'Order placed successfully']);
});

$router->addRoute('POST', '/get_order_history', function () use ($logger, $queue) {
    $token = $_GET['token'];
    if (!AuthController::verifyToken($token)) {
        handleUnauthorizedRequest($logger);
        return;
    }
    /* $task = new OrderTask('getOrderHistory', ['token' => $token]);
    $queue->add($task);
    $logger->log('Client requested order history'); */

    OrderController::getOrderHistory($token);
});

// Listen for incoming client requests
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];

$router->route($method, $path);

use Amp\Future;
use Amp\Parallel\Worker;

$pool = Worker\workerPool();

$tasks = $queue->getTasks();
$executions = [];

foreach ($tasks as $task) {
    $executions[] = $pool->submit($task);
}

$responses = Future\await(
    array_map(
        fn(Worker\Execution $e) => $e->getFuture(),
        $executions,
    )
);

foreach ($responses as $result => $response) {
    echo (string) $response;
}

$pool->shutdown();

function startServer($host, $port, $docroot)
{
    $cmd = sprintf('php -S %s:%d -t %s', $host, $port, $docroot);
    shell_exec($cmd);
}
function handleUnauthorizedRequest($logger)
{
    $logger->log('Unauthorized request');
    header('HTTP/1.1 401 Unauthorized');
    echo 'Unauthorized (invalid token)';
}