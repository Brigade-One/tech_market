<?php

require_once '../models/http_server.php';


$server = new HttpServer('techmarket', 80);
$serverPath = 'server/server.php';

// GET request
$response = $server->get($serverPath);
echo $response;
echo '<br>';

// POST request
$data = ['name' => 'John', 'age' => 30];
$response = $server->post($serverPath, $data);
echo $response;
echo '<br>';

// PUT request
$data = ['name' => 'John', 'age' => 35];
$response = $server->put($serverPath, $data);
echo $response;
echo '<br>';

// DELETE request
$response = $server->delete($serverPath);
echo $response;
echo '<br>';