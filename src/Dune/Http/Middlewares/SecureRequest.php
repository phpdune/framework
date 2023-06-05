<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dune\Http\Middlewares;

use Closure;
use Dune\Http\Request;
use Dune\App;
use Dune\Http\Middleware\MiddlewareInterface;
use Dune\Http\Middlewares\Exception\RequestNotSecure;

class SecureRequest implements MiddlewareInterface
{

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
        if ($this->canHandleRequest(new App) && !$request->secure()) {
            throw new RequestNotSecure("This request must be made over a secure connection.",403);
        }
        return $next($request);
    }
    /**
     * returns true if the app env is not local and app is not in testing
     *
     * @return bool
     */
    private function canHandleRequest(App $app): bool
    {
        return !$app->isLocal() && !$app->isTesting();
    }
}
