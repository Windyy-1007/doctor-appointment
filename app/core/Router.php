<?php

// Simple router that maps URI segments to controllers and actions
class Router
{
    protected string $controller = 'Home';
    protected string $action = 'index';
    protected array $params = [];
    protected array $adminResourceMap = [
        'users' => 'User',
        'specialties' => 'Specialty',
        'offices' => 'Office',
        'dashboard' => 'Dashboard',
    ];
    protected array $staffResourceMap = [
        'reports' => 'Report',
        'moderation' => 'Moderation',
    ];
    protected array $officeResourceMap = [
        'register' => 'Auth',
        'profile' => 'Profile',
        'doctors' => 'Doctor',
        'schedule' => 'Schedule',
        'appointments' => 'Appointment',
    ];

    public function __construct(string $path)
    {
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        if (!empty($segments)) {
            $section = array_shift($segments);

            if ($section === 'admin') {
                $resource = array_shift($segments);
                if ($resource !== null) {
                    $base = $this->adminResourceMap[$resource] ?? ucfirst($resource);
                    $this->controller = 'Admin' . $base;
                } else {
                    $this->controller = 'AdminDashboard';
                }
            } elseif ($section === 'staff') {
                $resource = array_shift($segments);
                if ($resource !== null) {
                    $base = $this->staffResourceMap[$resource] ?? ucfirst($resource);
                    $this->controller = 'Staff' . $base;
                } else {
                    $this->controller = 'StaffReport';
                }
            } elseif ($section === 'office') {
                $resource = array_shift($segments);
                if ($resource !== null) {
                    $base = $this->officeResourceMap[$resource] ?? ucfirst($resource);
                    $this->controller = 'Office' . $base;
                    if ($resource === 'register') {
                        $this->action = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' ? 'register' : 'showRegisterForm';
                        $segments = array_values($segments);
                        $this->params = $segments;
                        return;
                    }
                } else {
                    $this->controller = 'Office';
                }
            } else {
                $this->controller = ucfirst($section);
            }
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
