<?php
require __DIR__ . '/../vendor/autoload.php';

use Burst\Router;
use Burst\Application;
use Burst\Http\Request;
use Burst\Http\Response;
use Burst\Controller\HomeController;

// Create router and register routes
$router = new Router();

// Example middleware
$router->middleware(function(Request $request, callable $next) {
    $start = microtime(true);
    $response = $next($request);
    $time = microtime(true) - $start;
    return $response->withHeader('X-Response-Time', sprintf('%.3fms', $time * 1000));
});

// Register routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/hello/{name}', [HomeController::class, 'hello']);

// Create app and handle request
$app = new Application($router);
$request = Request::fromGlobals();
$response = $app->handle($request);
$response->send();