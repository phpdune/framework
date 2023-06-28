<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dune\Csrf\Middleware;

use Closure;
use Dune\Http\Request;
use Dune\Http\Middleware\MiddlewareInterface;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Csrf Header
     *
     * @var const
     */
    public const HEADER = "X-CSRF-TOKEN";
    /**
     * Csrf field name
     *
     * @var const
     */
    public const FIELD = "_token";
    /**
     * Request methods allowed to check the token
     *
     * @var const
     */
    public const METHODS = ["POST", "PUT", "PATCH", "DELETE"];
    /**
     * Csrf token validation
     *
     * @param \Dune\Http\Request $request
     * @param Closure $next
     *
     * @throw TokenMismatched
     *
     * @return Request
     */
    public function handle(Request $request, Closure $next): Request
    {
        $token = $this->getToken($request);
        if (
            $this->checkRequired($request) &&
            !hash_equals($token, $request->session()->get("_token"))
        ) {
            throw new \Dune\Csrf\Exception\TokenMismatched(
                "Csrf Token Mismatched!",
                419
            );
        }
        return $next($request);
    }

    /**
     * getting the token from header or field
     *
     * @param \Dune\Http\Request $request
     *
     * @return string
     */
    private function getToken(Request $request): string
    {
        if ($token = $request->header(self::HEADER)) {
            return $token;
        }
        if ($token = $request->get(self::FIELD)) {
            return $token;
        }
        return "";
    }
    /**
     * Checks the current request method needs csrf validation or not
     *
     * @param \Dune\Http\Request $request
     *
     * @return bool
     */
    private function checkRequired(Request $request): bool
    {
        return in_array($request->method(), self::METHODS);
    }
}
