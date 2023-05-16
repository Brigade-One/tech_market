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
        $this->logHandler->logEvent($method . ' request: ' . ' to ' . $path);
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