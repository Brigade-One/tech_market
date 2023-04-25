<?php

require_once '../models/http_server.php';

$server = new HttpServer('http://techmarket/server/server.php');

$response = $server->getRequest();

echo $response;