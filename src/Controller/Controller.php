<?php

namespace Burst\Controller;

use Burst\Http\Request;
use Burst\Http\Response;

abstract class Controller {

    /**
     * Create a JSON HTTP response.
     *
     * @param mixed $data   The data to be encoded as JSON (typically an array or object)
     * @param int   $status HTTP status code for the response, defaults to 200 (OK)
     * @return Response     JSON response instance
     */
    protected function json($data, int $status = 200): Response {
        return Response::json($data, $status);
    }

    /**
     * Create and return a plain text HTTP response.
     *
     * @param string $text   The response body as plain text.
     * @param int    $status The HTTP status code to use for the response. Defaults to 200.
     *
     * @return Response The constructed HTTP response.
     */
    protected function text(string $text, int $status = 200): Response {
        return new Response($text, $status);
    }
}