<?php

$host = 'techmarket';
$port = 80;
$docroot = __DIR__;

// Start server
$cmd = sprintf('php -S %s:%d -t %s', $host, $port, $docroot);
shell_exec($cmd);

// Handle the request
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo '[GET] Hello, world!';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '[POST] Data received';
} elseif ($method === 'PUT') {
    $data = file_get_contents('php://input');
    file_put_contents('data/put_data.txt', $data);
    echo '[PUT] Data written to file';
} elseif ($method === 'DELETE') {
    echo '[DELETE] delete called';
} else {
    // Handle invalid request
    http_response_code(404);
    echo 'Invalid request';
}

?>