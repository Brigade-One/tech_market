<?php

require_once 'lib/http_router.php';
require_once 'lib/auth_controller.php';
require_once 'lib/db_controller.php';
require_once 'lib/order_controller.php';
require_once 'lib/message_logger.php';
require_once 'lib/message_queue.php';
require '..\vendor\autoload.php';

header('Access-Control-Allow-Origin: http://techmarkethome');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Server parameters.
$host = 'techmarket';
$port = 80;
$docroot = __DIR__;
startServer($host, $port, $docroot);

// Create the MessageLogger and MessageQueue instances.
$logger = new MessageLogger('logs/server_tasks_log.txt');
$queue = new MessageQueue();

$router = new HttpRouter();
$router->addRoute('POST', '/sign_up', function () use ($logger) {
    $logger->log('Client requested sign up');

    return AuthController::signUp('data/users.txt');
});
$router->addRoute('POST', '/sign_in', function () use ($logger) {
    $logger->log('Client requested sign in');

    return AuthController::signIn('data/users.txt');
});
$router->addRoute('GET', '/get_all_items', function () use ($logger) {
    $logger->log('Client requested all items');

    DBController::getAllItemsFromDB('SELECT * FROM items');
});
$router->addRoute('GET', '/product', function () use ($logger) {
    $logger->log('Client requested product with ID: ' . $_GET['id']);
    return DBController::getItemInstanceByID($_GET['id']);
});
$router->addRoute('POST', '/order', function () use ($logger, $queue) {
    $token = $_GET['token'];

    if (!AuthController::verifyToken($token)) {
        $logger->log('Unauthorized request for order');

        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized (invalid token)';
        return;
    }
    require_once 'lib\tasks\order_task.php';
    $task = new OrderTask($token);
    $queue->add($task);
    echo 'Added order to the message queue';
    $logger->log('Added order to the message queue');
});

use ServerTasks\GetOrderHistoryTask;

$router->addRoute('POST', '/get_order_history', function () use ($logger, $queue) {
    $token = $_GET['token'];

    if (!AuthController::verifyToken($token)) {
        $logger->log('Unauthorized request for order history');

        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized (invalid token)';
        return;
    }

    $task = new GetOrderHistoryTask('getOrderHistory', ['token' => $token]);
    $queue->add($task);
    $logger->log('Client requested order history');
});

// Listen for incoming client requests
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];

// Route the request.
$router->route($method, $path);

use Amp\Future;
use Amp\Parallel\Worker;

$pool = Amp\Parallel\Worker\workerPool();

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


function startServer($host, $port, $docroot)
{
    $cmd = sprintf('php -S %s:%d -t %s', $host, $port, $docroot);
    shell_exec($cmd);
}