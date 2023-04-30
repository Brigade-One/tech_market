<?php

require_once '../models/http_server.php';

$server = new HttpServer('techmarket', 80);
$response = $server->get('server/server.php');

echo $response;