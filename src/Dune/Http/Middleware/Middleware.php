<?php

namespace Dune\Http\Middleware;

use Dune\Http\Middleware\MiddlewareInterface;
use Dune\Http\Request;
use Closure;

class Middleware
{
    /**
     * next piece of middleware
     *
     * @var Closure
     */
    private Closure $next;

    /**
     * setting initial piece of middleware
     */
    public function __construct()
    {
        $this->next = function (Request $request) {
            return $request;
        };
    }
    /**
     * for adding middleware
     *
     * @param MiddlewareInterface $middleware
     */
    public function add(MiddlewareInterface $middleware): void
    {
        $next = $this->next;
        $this->next = function (Request $request) use ($middleware, $next) {
            return $middleware->handle($request, $next);
        };
    }
    /**
     * handling ( running the all middlewares)
     *
     * @return Request
     */
    public function run(Request $request): Request
    {
        return ($this->next)($request);
    }
}
