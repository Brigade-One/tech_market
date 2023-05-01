<?php
require_once 'lib/http_router.php';

// Server parameters.
$host = 'techmarket';
$port = 80;
$docroot = __DIR__;

startServer($host, $port, $docroot);

// Helps to avoid if statements.
$router = new HttpRouter();

$router->addRoute('GET', '/', function () {
    return '[GET] Hello, world!';
});
$router->addRoute('POST', '/server/server.php/sign_up', function () {
    return _signUp('data/users.txt');
});
$router->addRoute('POST', '/server/server.php/sign_in', function () {
    return _signIn('data/users.txt');
});

// HTTP method and URI of the request.
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Route the request.
$router->route($method, $uri);

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
    $signin = new SignIn($filename);
    $jsonData = file_get_contents('php://input');
    $result = $signin->processSignInData($jsonData);
    _sendAuthRequestResponse($result);
}

function _sendAuthRequestResponse($result)
{
    header('Content-Type: application/json');
    if ($result['success'] === true) {
        echo json_encode(['success' => true, 'message' => $result['message']]);
    } else {
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
}

function startServer($host, $port, $docroot)
{
    $cmd = sprintf('php -S %s:%d -t %s', $host, $port, $docroot);
    shell_exec($cmd);
}

?>