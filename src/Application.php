<?php
namespace Burst;

use Burst\Http\Request;
use Burst\Http\Response;

class Application {
    private Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function handle(Request $request): Response {
        $route = $this->router->dispatch($request->getMethod(), $request->getPath());
        
        if (!$route['handler']) {
            return new Response('Not Found', 404);
        }

        $request->setParams($route['params']);
        
        // Run middleware stack
        $next = function ($request) use ($route) {
            if (is_callable($route['handler'])) {
                return $route['handler']($request);
            }
            if (is_array($route['handler']) && count($route['handler']) === 2) {
                [$controller, $method] = $route['handler'];
                $instance = is_string($controller) ? new $controller() : $controller;
                return $instance->$method($request);
            }
            return new Response('Invalid handler', 500);
        };

        // Build middleware chain
        foreach (array_reverse($route['middleware']) as $middleware) {
            $next = function ($request) use ($middleware, $next) {
                return $middleware($request, $next);
            };
        }

        $response = $next($request);
        return $response instanceof Response ? $response : new Response($response);
    }
}