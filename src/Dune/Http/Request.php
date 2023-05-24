<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dune\Http;

use Dune\Http\Redirect;
use Dune\Http\Validation\Validater;
use Dune\Http\RequestInterface;
use Dune\Session\SessionInterface;
use Dune\Session\Session;
use Dune\Http\RequestContainer;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Request implements RequestInterface
{
    use RequestContainer;

    /**
      * \Symfony\Component\HttpFoundation\Request
      *
      * @var ?BaseRequest
      */
    private ?BaseRequest $request = null;
    /**
     * new Symfony Request instance
     */
    public function __construct()
    {
        BaseRequest::enableHttpMethodParameterOverride();
        if(is_null($this->request)) {
            $this->request = BaseRequest::createFromGlobals();
        }
    }
    /**
     * return the symfony request instance
     */
     public static function globals(): BaseRequest
     {
         return $this->request;
     }
    /**
     * @param string $key
     * @param null $default
     *
     * @return string|null
     */
    public function get($key, $default = null): ?string
    {
        return $this->request->get($key, $default);
    }
    /**
     *
     * @return BaseRequest
     */
    public function all(): BaseRequest
    {
        return $this->request;
    }
     /**
     * @return string
     */
    public function method(): string
    {
        return $this->request->getMethod();
    }

    /**
     *
     * @param string $key
     *
     * @return ?string
     */
     public function server(string $key): ?string
     {
         return $this->request->server->get($key);
     }
     /**
     *
     * @return bool
     */
     public function isGet(): bool
     {
         return ($this->request->getMethod() == 'GET' ? true : false);
     }
    /**
     *
     * @return bool
     */
     public function isPost(): bool
     {
         return ($this->request->getMethod() == 'POST' ? true : false);
     }
     /**
     *
     * @return bool
     */
     public function isPut(): bool
     {
         return ($this->request->getMethod() == 'PUT' ? true : false);
     }
    /**
     *
     * @return bool
     */
     public function isPatch(): bool
     {
         return ($this->request->getMethod() == 'PATCH' ? true : false);
     }
    /**
     *
     * @return bool
     */
     public function isDelete(): bool
     {
         return ($this->request->getMethod() == 'DELETE' ? true : false);
     }
    /**
     *
     * @return bool
     */
     public function isAjax(): bool
     {
         return $this->request->isXmlHttpRequest();
     }
    /**
     *
     * @return bool
     */
     public function secure(): bool
     {
         return $this->request->isSecure();
     }
     /**
     * @return array<string,mixed>
     */
     public function headers(): ?array
     {
         return $this->request->headers->all();
     }
     /**
     * @param string $key
     *
     * @return ?string
     */
     public function header(string $key): ?string
     {
         return $this->request->headers->get($key);
     }
     /**
     * @param string $key
     *
     * @return bool
     */
     public function hasHeader(string $key): bool
     {
         return $this->request->headers->has($key);
     }
     /**
     * @return string
     */
     public function ip(): string
     {
         return $this->request->getClientIp();
     }
     /**
     * @return string
     */
     public function userAgent(): string
     {
         return $this->request->headers->get('User-Agent');
     }
     /**
     * @return string
     */
     public function host(): string
     {
         return $this->request->getHost();
     }
     /**
     * @return string
     */
     public function platform(): string
     {
         return $this->header('sec-ch-ua-platform');
     }
     /**
     * @return string
     */
     public function path(): string
     {
         return $this->request->getPathInfo();
     }
     /**
     * @return string
     */
     public function contentType(): ?string
     {
         return $this->header('Content-Type');
     }
    /**
     * @return ?int
     */
     public function isRedirected(): ?int
     {
         //
     }
     /**
     * @return string
     */
     public function scheme(): string
     {
         return ($this->secure() ? 'https' : 'http');
     }
     /**
     * @return string
     */
     public function protocol(): string
     {
         return $this->server('SERVER_PROTOCOL');
     }
     /**
     * @return ?string
     */
     public function fullQuery(): ?string
     {
         return $this->request->getQueryString();
     }
     /**
     * @param string $key
     *
     * @return ?string
     */
     public function query(string $key): ?string
     {
         return $this->request->query->get($key);
     }
     /**
     * @param string $key
     *
     * @return bool
     */
     public function queryHas(string $key): boll
     {
         return $this->request->query->has($key);
     }
     /**
     * @return string
     */
     public function fullUrl(): string
     {
         return $this->request->getSchemeAndHttpHost() . $this->path();
     }
     /**
     * @return bool
     */
     public function hasFile(string $key): bool
     {
         return $this->request->files->has($key);
     }
     /**
     * @return Symfony\Component\HttpFoundation\File\UploadedFile
     */
     public function file(string $key): UploadedFile
     {
         return $this->request->files->get($key);
     }
     /**
     * @return string
     */
     public function fullUrlWithQuery(): string
     {
         return $this->request->getSchemeAndHttpHost() . $this->request->getRequestUri();
     }
     /**
     * @return string
     */
     public function port(): string
     {
         return $this->request->getPort();
     }
    /**
     * session instance
     */
     public function session(): SessionInterface
     {
         $this->__setUp();
         return $this->container->get(Session::class);
     }
    /**
    * request validation
    * request input validation
    *
    * @param ValidaterInterface|array<mixed> $data
    *
    * @return ?Redirect
    */
    public function validate(ValidaterInterface|array $data): ?Redirect
    {
        return Validater::make($data);
    }
    /**
     * access the method of symfony request
     *
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
     public function __call(string $method, array $args): mixed
     {
         return $this->request->$method(...$args);
     }
}
