<?php

declare(strict_types=1);

namespace Dune\Http\Middlewares;

use Closure;
use Dune\Http\Request;
use Dune\App;
use Dune\Http\Middleware\MiddlewareInterface;
use Dune\Http\Middlewares\Exception\RequestNotSecure;

class SecureRequest implements MiddlewareInterface
{
    /**
     * \Dune\App instance
     * 
     * @var App
     */
    private App $app;
    /**
     * check the request is secure
     * 
     * @param Request $request
     * @param Closure $next
     * 
     * @throw \RequestNotSecure
     * 
     * @return Request
     */
    public function handle(Request $request, Closure $next): Request
    {
        $this->app = new App();
        if ($this->canHandleRequest() && !$request->secure()) {
            throw new RequestNotSecure("This request must be made over a secure connection.",403);
        }
        return $next($request);
    }
    /**
     * returns true if the app env is not local and app is not in testing
     *
     * @return bool
     */
    public function canHandleRequest(): bool
    {
        return !$this->app->isLocal() && !$this->app->isTesting();
    }
}
