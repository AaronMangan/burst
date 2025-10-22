# Burst Micro Framework

A tiny PHP framework that handles routing, middleware, controllers, and JSON responses. By using only the features you need, you can create a "minolith".

## Get Installed!

Burst is designed to be in residence of your code base, that is, it is the foundation on which you build your app.

`https://github.com/AaronMangan/burst.git`

OR

`git@github.com:AaronMangan/burst.git`


## Quick Start

```bash
composer install
php -S localhost:8080 -t public
```

Visit http://localhost:8080 or http://localhost:8080/hello/your-name

## Features

- Simple routing with parameters: `/hello/{name}`
- Middleware support (global and per-route)
- Controller support with base Controller class
- Request/Response abstraction
- JSON responses
- Extremely lightweight

## Usage

### Define Routes

```php
$router = new Router();

// Closure handler
$router->get('/hello', function(Request $req) {
    return new Response('Hello World');
});

// Controller method
$router->get('/hello/{name}', [HomeController::class, 'hello']);
```

### Create Controllers

```php
class UserController extends Controller {
    public function show(Request $request): Response {
        $id = $request->param('id');
        return $this->json(['id' => $id]);
    }
}
```

### Add Middleware

```php
$router->middleware(function(Request $request, callable $next) {
    // Before request
    $response = $next($request);
    // After request
    return $response;
});
```