<?php
class HttpRouter
{
    private $routes = [];

    public function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }
    public function route($method, $path)
    {
        require_once 'lib/log_handler.php';
        $logHandler = new LogHandler();
        $logHandler->logEvent($method . ' request: ' . ' to ' . $path);
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $callback = $route['callback'];
                return $callback();
            }
        }
        http_response_code(404);
        echo 'Invalid request';
    }

}