<?php
namespace Burst\Http;

class Request {
    private string $method;
    private string $path;
    private array $query;
    private array $body;
    private array $params;

    public function __construct(string $method, string $path, array $query = [], array $body = [], array $params = []) {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->query = $query;
        $this->body = $body;
        $this->params = $params;
    }

    public static function fromGlobals(): self {
        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
            $_GET ?? [],
            $_POST ?? [],
            []
        );
    }

    public function getMethod(): string { return $this->method; }
    public function getPath(): string { return $this->path; }
    public function query(string $key, $default = null) { return $this->query[$key] ?? $default; }
    public function input(string $key, $default = null) { return $this->body[$key] ?? $default; }
    public function param(string $key, $default = null) { return $this->params[$key] ?? $default; }
    public function setParams(array $params): void { $this->params = $params; }
}