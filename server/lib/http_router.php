<?php
class HttpRouter
{
    private $routes = [];

    public function addRoute($method, $uri, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'callback' => $callback
        ];
    }
    public function route($method, $uri)
    {
        require_once 'lib/log_handler.php';
        $logHandler = new LogHandler();
        $logHandler->logEvent($method . ' request: ' . ' to ' . $uri);
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                $callback = $route['callback'];
                return $callback();
            }
        }
        http_response_code(404);
        echo 'Invalid request';
    }
}