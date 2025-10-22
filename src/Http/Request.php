<?php

namespace Burst\Http;

class Request {
    /**
     * The request method used.
     *
     * @var string
     */
    private string $method;

    /**
     * The path of the request (url).
     *
     * @var string
     */
    private string $path;

    /**
     * The query string parameters.
     *
     * @var array
     */
    private array $query;

    /**
     * HTTP request body.
     *
     * @var array
     */
    private array $body;

    /**
     * Request parameters.
     *
     * @var array
     */
    private array $params;

    /**
     * Constructor. Sets the various properties.
     *
     * @param string $method
     * @param string $path
     * @param array $query
     * @param array $body
     * @param array $params
     */
    public function __construct(string $method, string $path, array $query = [], array $body = [], array $params = []) {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->query = $query;
        $this->body = $body;
        $this->params = $params;
    }

    /**
     * Return a request object, with hydrated properties from the globals.
     *
     * @return self
     */
    public static function fromGlobals(): self {
        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
            $_GET ?? [],
            $_POST ?? [],
            []
        );
    }

    /**
     * Return the method of the request.
     *
     * @return string
     */
    public function getMethod(): string { return $this->method; }
    
    /**
     * Return the path of the request.
     *
     * @return string
     */
    public function getPath(): string { return $this->path; }
    
    /**
     * Return the query string parameters.
     *
     * @param string $key
     * @param mixed|null $default
     * @return void
     */
    public function query(string $key, $default = null) { return $this->query[$key] ?? $default; }
    
    /**
     * Return input from the request, referenced by key.
     *
     * @param string $key
     * @param mixed|null $default
     * @return void
     */
    public function input(string $key, $default = null) { return $this->body[$key] ?? $default; }
    
    /**
     * Return the parameters in the request.
     *
     * @param string $key
     * @param mixed|null $default
     * @return void
     */
    public function param(string $key, $default = null) { return $this->params[$key] ?? $default; }
    
    /**
     * Set the route parameters.
     *
     * @param array $params
     * @return void
     */
    public function setParams(array $params): void { $this->params = $params; }
}