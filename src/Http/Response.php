<?php

namespace Burst\Http;

class Response {
    /**
     * The body of the response. This contains the data being returned, typically in a JSON format.
     *
     * @var mixed
     */
    private $body;

    /**
     * The HTTP status of the response.
     *
     * @var integer
     */
    private int $status;

    /**
     * Any headers to be included in the response.
     *
     * @var array
     */
    private array $headers;

    /**
     * Constructor.
     *
     * @param string $body
     * @param integer $status
     * @param array $headers
     */
    public function __construct($body = '', int $status = 200, array $headers = []) {
        $this->body = $body;
        $this->status = $status;
        $this->headers = array_merge(['Content-Type' => 'text/html'], $headers);
    }

    /**
     * Setup the response to return as JSON, including applicable headers.
     *
     * @param mixed $data
     * @param integer $status
     * @return self
     */
    public static function json($data, int $status = 200): self {
        return new self(
            json_encode($data),
            $status,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Adds a header/s to the response. This method returns a new instance of the Response with the added header.
     *
     * @param string $name
     * @param string $value
     * @return self
     */
    public function withHeader(string $name, string $value): self {
        $clone = clone $this;
        $clone->headers[$name] = $value;
        return $clone;
    }

    /**
     * Send the response back to the caller.
     *
     * @return void
     */
    public function send(): void {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }
}