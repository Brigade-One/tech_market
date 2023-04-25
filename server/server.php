<?php
$host = 'techmarket';
$port = 80;
$docroot = __DIR__;

$cmd = sprintf('php -S %s:%d -t %s', $host, $port, $docroot);
shell_exec($cmd);

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo 'Hello, world!';
}
?>