<?php

namespace Burst;

class Router {
    /**
     * An array of defined routes.
     *
     * @var array
     */
    private array $routes = [];

    /**
     * An array of middleware to be run before requests.
     *
     * @var array
     */
    private array $middleware = [];

    /**
     * Add a new route to the application.
     *
     * @param string $method
     * @param string $path
     * @param callback|mixed $handler
     * @return void
     */
    public function add(string $method, string $path, $handler): void {
        $this->routes[$method][$path] = ['handler' => $handler, 'middleware' => []];
    }

    /**
     * Handles GET routes using the specified path.
     *
     * @param string $path
     * @param callback|mixed $handler
     * @return void
     */
    public function get(string $path, $handler): void {
        $this->add('GET', $path, $handler);
    }

    /**
     * Handles POST methods, using the specified path.
     *
     * @param string $path
     * @param callback|mixed $handler
     * @return void
     */
    public function post(string $path, $handler): void {
        $this->add('POST', $path, $handler);
    }

    /**
     * Add middleware to the route.
     *
     * @param mixed $middleware
     * @return void
     */
    public function middleware($middleware): void {
        $this->middleware[] = $middleware;
    }

    /**
     * Action the route
     *
     * @param string $method
     * @param string $path
     * @return array
     */
    public function dispatch(string $method, string $path): array {
        $method = strtoupper($method);
        foreach ($this->routes[$method] ?? [] as $pattern => $route) {
            $params = $this->match($pattern, $path);
            if ($params !== null) {
                return [
                    'handler' => $route['handler'],
                    'params' => $params,
                    'middleware' => array_merge($this->middleware, $route['middleware'])
                ];
            }
        }
        return ['handler' => null, 'params' => [], 'middleware' => []];
    }

    /**
     * Match the incoming request path to a route.
     *
     * @param string $pattern
     * @param string $path
     * @return array|null
     */
    private function match(string $pattern, string $path): ?array {
        $pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $path, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }
        return null;
    }
}