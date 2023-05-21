<?php

declare(strict_types=1);

namespace Dune\Http\Middlewares;

use Closure;
use Dune\Http\Request;
use Dune\Http\Middleware\MiddlewareInterface;
use Dune\Http\Middlewares\Exception\InvalidHost;

class ValidateHost implements MiddlewareInterface
{
    /**
     * trusted hosts array 
     * 
     * @var array
     */
    private array $trustedHosts;
    /**
     * check the request is secure
     * 
     * @param Request $request
     * @param Closure $next
     * 
     * @throw \InvalidHost
     * 
     * @return Request
     */
    public function handle(Request $request, Closure $next): Request
    {
         $this->setTrustedHosts();
        if (!$this->isTrustedHost($request)) {
            throw new InvalidHost("Must be a valid host",400);
        }
        return $next($request);
    }
    /**
     * Check all hosts is trusted or not
     * 
     * @param Request $request
     * 
     * @return bool
     */
    private function isTrustedHost(Request $request): bool
    {
        $host = $request->host();

        foreach ($this->trustedHosts as $trustedHost) {
            if ($this->isHostMatch($host, $trustedHost)) {
                return true;
            }
        }

        return false;
    }
    /**
     * check the request host matching with the trusted hosts array
     * 
     * @param string $host
     * @param string $trustedHost
     * 
     * @return bool
     */
    private function isHostMatch(string $host, string $trustedHost): bool
    {
        $pattern = sprintf('/^%s$/', str_replace('\*', '.*', preg_quote($trustedHost, '/')));

        return preg_match($pattern, $host) === 1;
    }
    /**
     * setting the trusted hosts
     * default trusted hosts will be app domain and all subdomain
     * 
     * @return void
     */
    private function setTrustedHosts(): void
    {
      $domain = env('APP_DOMAIN');
      $subdomain = '*.'.$domain;
      $this->trustedHosts = [$domain,$subdomain];
    }
}