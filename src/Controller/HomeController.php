<?php

namespace Burst\Controller;

use Burst\Http\Request;
use Burst\Http\Response;
use Burst\Config;

/**
 * Example Controller
 *
 * @extends Controller
 */
class HomeController extends Controller {

    /**
     * Return the specified data.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response {
        return $this->text('Welcome to Burst!');
    }

    /**
     * Returns a greeting.
     *
     * @param Request $request
     * @return Response
     */
    public function hello(Request $request): Response {
        $name = $request->param('name', 'World');
        return $this->json([
            'message' => "Hello, $name!",
            'config' => Config::all(),
        ]);
    }
}