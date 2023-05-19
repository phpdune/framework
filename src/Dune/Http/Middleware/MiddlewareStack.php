<?php

declare(strict_types=1);

namespace Dune\Http\Middleware;

use Dune\Http\Middleware\MiddlewareInterface;
use Dune\Http\Request;
use Closure;

class MiddlewareStack
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
       * add the middleware
       *
       * @param MiddlewareInterface $middleware
       *
       */
    public function add(MiddlewareInterface $middleware): void
    {
        $next = $this->next;
        $this->next = function (Request $request) use ($middleware, $next) {
            return $middleware->handle($request, $next);
        };
    }
      /**
       * running the middlewares
       *
       * @return mixed
       */
    public function handle(Request $request): mixed
    {
        return call_user_func($this->next, $request);
    }
}
