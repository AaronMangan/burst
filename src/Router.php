<?php
namespace Burst;

class Router {
    private array $routes = [];
    private array $middleware = [];

    public function add(string $method, string $path, $handler): void {
        $this->routes[$method][$path] = ['handler' => $handler, 'middleware' => []];
    }

    public function get(string $path, $handler): void {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, $handler): void {
        $this->add('POST', $path, $handler);
    }

    public function middleware($middleware): void {
        $this->middleware[] = $middleware;
    }

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

    private function match(string $pattern, string $path): ?array {
        $pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $path, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }
        return null;
    }
}