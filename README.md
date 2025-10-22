# Burst Micro Framework

A tiny PHP framework that handles routing, middleware, controllers, and JSON responses. By using only the features you need, you can create a "minolith".

## But....Why!?

I know, there are excellent PHP frameworks available these days, and I LOVE to use them - Burst was heavily inspired by the Laravel team, it's such a beautiful framework.

But I thought to myself, how small can we go? - the result is Burst.

The idea is to cut out anything you don't need, add only what you will actually use.

During the course of my developer career, I have work on so many tools that just needed the basics, from tiny API's designed only to access a legacy database with three tables, to tools that served a single page for insurance calculations. Most of these did not need all the overhead a full framework.

Another good use case is for prototyping - if you need something to work as a proof-of-concept, a business case or example, we got you covered.

## Get Installed!

Burst is designed to cohabitate in your code base, that is, it is the foundation on which you build your app.

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
- JSON configuration

## Usage

### Routing

Inside `public/index.php` - In the routes section, you may add your desired routes. If you've ever written routes in Laravel before then you'll be right at home.
We've included a couple to get you started.

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

### Controllers

Use controllers to determine the proper flow of logic in your app. Routes are tied to a specific method in your controller, but for anything substantial consider handing off to a service or repository instead. This will keep you code modular and extensible.

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
## *More Coming*

This framework is still in development, please stay tuned for updates!
