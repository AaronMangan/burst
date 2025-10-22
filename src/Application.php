<?php

namespace Burst;

use Burst\Http\Request;
use Burst\Http\Response;

class Application {

    /**
     * The Router instance. Instructs the application what routes are defined.
     *
     * @var Router
     */
    private Router $router;

    /**
     * Constructor. Provides the Router instance.
     *
     * @param Router $router
     */
    public function __construct(Router $router) {
        $this->router = $router;// Initialize config
    }

    /**
     * Handles each request appropriately.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response {
        $route = $this->router->dispatch($request->getMethod(), $request->getPath());
        
        if (!$route['handler']) {
            return new Response('Not Found', 404);
        }

        $request->setParams($route['params']);
        
        // MIDDLEWARE AND HANDLER EXECUTION
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

        // Build a middleware chain.
        foreach (array_reverse($route['middleware']) as $middleware) {
            $next = function ($request) use ($middleware, $next) {
                return $middleware($request, $next);
            };
        }

        $response = $next($request);

        return $response instanceof Response ? $response : new Response($response);
    }

    public function getConfig(): Config {
        return $this->config;
    }
}