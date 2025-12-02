<?php

session_start();

// Bootstrap config and base classes used by the router and controllers
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Router.php';

// Determine the path requested by the client
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = str_replace('\\', '/', $requestUri);

$path = '/';
if ($scriptDir !== '/' && strpos($requestUri, $scriptDir) === 0) {
    $path = substr($requestUri, strlen($scriptDir));
} elseif ($scriptDir === '/') {
    $path = $requestUri;
}

// Dispatch the controller/action using the parsed path
$router = new Router($path);
$router->dispatch();
