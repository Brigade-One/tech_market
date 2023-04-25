<?php

require_once 'models/http_server.php';

$server = new HttpServer('http://example.com/server.php');

$response = $server->getRequest();

echo $response;