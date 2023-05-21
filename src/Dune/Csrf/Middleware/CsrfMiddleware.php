<?php

declare(strict_types=1);

namespace Dune\Csrf\Middleware;

use Closure;
use Dune\Http\Request;
use Dune\Http\Middleware\MiddlewareInterface;
use Dune\Csrf\Exception\TokenMismatched;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Csrf Header
     *
     * @var const
     */
    public const HEADER = 'X-CSRF-TOKEN';
    /**
     * Csrf field name
     *
     * @var const
     */
    public const FIELD = '_token';
    /**
     * Request methods allowed to check the token
     *
     * @var const
     */
    public const METHODS = ['POST','PUT','PATCH','DELETE'];
    /**
     * Csrf token validation
     *
     * @param \Dune\Http\Request $request
     * @param Closure $next
     *
     * @throw TokenMismatched
     * 
     * @return Closure
     */
    public function handle(Request $request, Closure $next): Request
    {
        $token = $request->session()->get('_token');
        if($this->checkRequired($request) && !hash_equals($this->getToken($request), $token)) {
            throw new TokenMismatched("Csrf Token Mismatched!",419);
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
    public function getToken(Request $request): string
    {
        if($request->header(self::HEADER)) {
            return $request->header(self::HEADER);
        }
        if($request->get(self::FIELD)) {
            return $request->get(self::FIELD);
        }
        return '';
    }
    /**
     * Checks the current request method needs csrf validation or not
     *
     * @param \Dune\Http\Request $request
     *
     * @return bool
     */
    public function checkRequired(Request $request): bool
    {
        return in_array($request->method(), self::METHODS);
    }
}
