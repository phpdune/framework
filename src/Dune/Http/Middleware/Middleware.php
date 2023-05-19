<?php

declare(strict_types=1);

namespace Dune\Http\Middleware;

use Dune\Http\Middleware\MiddlewareStack;
use Dune\Http\Middleware\MiddlewareInterface;
use Dune\Http\Request;

class Middleware
{
    /**
     * Dune\Http\Middleware\MiddlewareStack instance
     *
     * @var MiddlewareStack
     */
    private MiddlewareStack $stack;
    /**
     * Dune\Http\Middleware\MiddlewareStack instance setting
     *
     * @param MiddlewareStack $stack
     */
    public function __construct(MiddlewareStack $stack)
    {
        $this->stack = $stack;
    }
      /**
       * for adding middleware
       *
       * @param MiddlewareInterface $middleware
       */
    public function add(MiddlewareInterface $middleware): void
    {
        $this->stack->add($middleware);
    }
      /**
       * handling ( running the all middlewares)
       *
       */
    public function run()
    {
        return $this->stack->handle(new Request());
    }
}
