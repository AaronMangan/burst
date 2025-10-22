<?php

use Burst\Http\Request;
use Burst\Controller\HomeController;

// Bootstrap the framework
$burst = require __DIR__ . '/../bootstrap.php';
$router = $burst['router'];
$app = $burst['app'];

// Register your routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/hello/{name}', [HomeController::class, 'hello']);

// Handle the request
$request = Request::fromGlobals();
$response = $app->handle($request);
$response->send();