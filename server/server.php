<?php

$host = 'techmarket';
$port = 80;
$docroot = __DIR__;

// Start server
$cmd = sprintf('php -S %s:%d -t %s', $host, $port, $docroot);
shell_exec($cmd);

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if ($method === 'GET') {
    echo '[GET] Hello, world!';

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($uri === '/server/server.php/sign_up') {
        echo _signUp('data/users.txt');
    }
    if ($uri === '/server/server.php/sign_in') {
        echo _signIn('data/users.txt');
    }
} elseif ($method === 'PUT') {
    echo '[PUT] put called';
} elseif ($method === 'DELETE') {
    echo '[DELETE] delete called';
} else {
    http_response_code(404);
    echo 'Invalid request';
}

function _signUp($filename)
{
    require_once 'lib/sign_up.php';

    $signup = new SignUp($filename);

    // Получаем данные, переданные клиентом в формате JSON
    $jsonData = file_get_contents('php://input');

    // Обрабатываем данные
    $result = $signup->processSignupData($jsonData);

    // Отправляем ответ клиенту в формате JSON
    header('Content-Type: application/json');

    if ($result['success'] === true) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $result['message']]);
    }
}
function _signIn($filename)
{
    require_once 'lib/sign_in.php';

    $signin = new SignIn($filename);

    // Получаем данные, переданные клиентом в формате JSON
    $jsonData = file_get_contents('php://input');

    // Обрабатываем данные
    $result = $signin->processSignInData($jsonData);

    // Отправляем ответ клиенту в формате JSON
    header('Content-Type: application/json');

    if ($result['success'] === true) {
        echo json_encode(['success' => true, 'message' => $result['message']]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => true, 'message' => $result['message']]);
    }
}

?>