<?php
namespace Burst\Http;

class Response {
    private $body;
    private int $status;
    private array $headers;

    public function __construct($body = '', int $status = 200, array $headers = []) {
        $this->body = $body;
        $this->status = $status;
        $this->headers = array_merge(['Content-Type' => 'text/html'], $headers);
    }

    public static function json($data, int $status = 200): self {
        return new self(
            json_encode($data),
            $status,
            ['Content-Type' => 'application/json']
        );
    }

    public function withHeader(string $name, string $value): self {
        $clone = clone $this;
        $clone->headers[$name] = $value;
        return $clone;
    }

    public function send(): void {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }
}