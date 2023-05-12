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
    /*  $task = new AuthTask('signUp', ['jsonData' => file_get_contents('php://input')]);
    $queue->add($task); */
    echo json_encode(AuthController::signIn(file_get_contents('php://input')));
});
$router->addRoute('POST', '/sign_in', function () use ($logger, $queue) {
    $logger->log('Client requested sign in');
    $task = new AuthTask('signIn', ['jsonData' => file_get_contents('php://input')]);
    $queue->add($task);

});
$router->addRoute('GET', '/get_all_items', function () use ($logger, $queue) {
    $logger->log('Client requested all items from DB');
    $task = new DatabaseTask('getAllItems', []);
    $queue->add($task);
});
$router->addRoute('GET', '/product', function () use ($logger, $queue) {
    $logger->log('Client requested product with ID: ' . $_GET['id']);
    $task = new DatabaseTask('getItemById', ['id' => $_GET['id']]);
    $queue->add($task);
});
$router->addRoute('POST', '/order', function () use ($logger, $queue) {
    $token = $_GET['token'];
    if (!AuthController::verifyToken($token)) {
        $logger->log('Unauthorized request for order');
        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized (invalid token)';
        return;
    }
    $task = new OrderTask("order", ['token' => $token, 'jsonData' => file_get_contents('php://input')]);
    $queue->add($task);
    $logger->log('Added write order task to the message queue');
});

$router->addRoute('POST', '/get_order_history', function () use ($logger, $queue) {
    $token = $_GET['token'];
    if (!AuthController::verifyToken($token)) {
        $logger->log('Unauthorized request for order history');
        header('HTTP/1.1 401 Unauthorized');
        echo 'Unauthorized (invalid token)';
        return;
    }
    $task = new OrderTask('getOrderHistory', ['token' => $token]);
    $queue->add($task);
    $logger->log('Client requested order history');
});

// Listen for incoming client requests
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];

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