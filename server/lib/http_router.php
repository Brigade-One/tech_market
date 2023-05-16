<?php
class HttpRouter
{
    private $routes = [];
    private $logHandler;

    public function __construct($logHandler)
    {
        $this->logHandler = $logHandler;
    }

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
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $callback = $route['callback'];
                $this->logHandler->logEvent($method . ' request: ' . ' to ' . $path);
                return $callback();
            }
        }
        http_response_code(404);
        $this->logHandler->logEvent('Invalid request to ' . $path);
        echo 'Invalid request';
    }
}