<?php

// Simple router that maps URI segments to controllers and actions
class Router
{
    protected string $controller = 'Home';
    protected string $action = 'index';
    protected array $params = [];

    public function __construct(string $path)
    {
        $segments = array_filter(explode('/', trim($path, '/')));

        if (!empty($segments)) {
            $this->controller = ucfirst(array_shift($segments));
        }

        if (!empty($segments)) {
            $this->action = array_shift($segments);
        }

        $this->params = $segments;
    }

    public function dispatch(): void
    {
        // Determine controller file for the requested route
        $controllerClass = $this->controller . 'Controller';
        $controllerPath = __DIR__ . '/../controllers/' . $controllerClass . '.php';

        if (!file_exists($controllerPath)) {
            $this->abort404();
            return;
        }

        require_once $controllerPath;

        if (!class_exists($controllerClass)) {
            $this->abort404();
            return;
        }

        $controller = new $controllerClass();
        $action = $this->action;

        // Ensure the requested action exists before invoking it
        if (!method_exists($controller, $action)) {
            $this->abort404();
            return;
        }

        call_user_func_array([$controller, $action], $this->params);
    }

    protected function abort404(): void
    {
        // Send a simple 404 response when route resolution fails
        http_response_code(404);
        echo '<h1>404 - Page not found</h1>';
    }
}
