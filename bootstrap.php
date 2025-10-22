<?php

require __DIR__ . '/vendor/autoload.php';

use Burst\Router;
use Burst\Application;
use Burst\Http\Request;
use Burst\Http\Response;
use Burst\Config;

// Create the config instance
Config::getInstance();

// Create router
$router = new Router();

// Add default middleware
$router->middleware(function(Request $request, callable $next) {
    $start = microtime(true);
    $response = $next($request);
    $time = microtime(true) - $start;
    return $response->withHeader('X-Response-Time', sprintf('%.3fms', $time * 1000));
});

// Create application
$app = new Application($router);

// Return router and app for use in index.php
return [
    'router' => $router,
    'app' => $app
];