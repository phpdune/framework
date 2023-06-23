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

class Request implements RequestInterface
{
    use RequestContainer;

    /**
      * All data from $_GET, $_POST, $_SESSION, $_SERVER, $_COOKIE
      *
      * @var array<string,mixed>
      */
    private array $allData = [];
    /**
     * collect all data from php global variables
     */
    public function __construct()
    {
        $this->allData = [
          'GET' => $_GET,
          'POST' => $_POST,
          'SESSION' => [],
          'COOKIE' => $_COOKIE,
          'SERVER' => $_SERVER,
          'HEADERS' => $this->headers()
          ];
    }

    /**
     * @param string $key
     * @param null $default
     *
     * @return string|null
     */
    public function get($key, $default = null): ?string
    {
        if(isset($this->allData['POST'][$key])) {
            return $this->allData['POST'][$key];
        } elseif (isset($this->allData['GET'][$key])) {
            return $this->allData['GET'][$key];
        }
        return null;
    }
    /**
     *
     * @return array<mixed>
     */
    public function all(): array
    {
        return $this->allData;
    }
     /**
     * @return string
     */
    public function method(): string
    {
        return (isset($this->allData['POST']['_method']) ? $this->allData['POST']['_method'] : $this->allData['SERVER']['REQUEST_METHOD']);
    }
    
    /**
     *
     * @param string $key
     *
     * @return ?string
     */
     public function server(string $key): ?string
     {
         return (isset($this->allData['SERVER'][$key]) ? $this->allData['SERVER'][$key] : null);
     }
     /**
     *
     * @return bool
     */
     public function isGet(): bool
     {
         return ($this->method() == 'GET' ? true : false);
     }
    /**
     *
     * @return bool
     */
     public function isPost(): bool
     {
         return ($this->method() == 'POST' ? true : false);
     }
     /**
     *
     * @return bool
     */
     public function isPut(): bool
     {
         return ($this->method() == 'PUT' ? true : false);
     }
    /**
     *
     * @return bool
     */
     public function isPatch(): bool
     {
         return ($this->method() == 'PATCH' ? true : false);
     }
    /**
     *
     * @return bool
     */
     public function isDelete(): bool
     {
         return ($this->method() == 'DELETE' ? true : false);
     }
    /**
     *
     * @return bool
     */
     public function isAjax(): bool
     {
         if(isset($this->allData['SERVER']['HTTP_X_REQUESTED_WITH']) && strtolower($this->allData['SERVER']['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
             return true;
         }
         return false;
     }
    /**
     * @return bool
     */
     public function secure(): bool
     {
         if(isset($this->allData['SERVER']['HTTPS']) && $this->allData['SERVER']['HTTPS'] == 'on') {
             return true;
         }
         return false;
     }
     /**
     * @return array<string,mixed>
     */
     public function headers(): ?array
     {
         if(function_exists('getallheaders'))
         {
           return \getallheaders();
         }
          return [];
     }
     /**
     * @param string $key
     *
     * @return ?string
     */
     public function header(string $key): ?string
     {
         if(isset($this->allData['HEADERS'][$key])) {
             return $this->allData['HEADERS'][$key];
         }
         return null;
     }
     /**
     * @param string $key
     *
     * @return bool
     */
     public function hasHeader(string $key): bool
     {
         return (isset($this->allData['HEADERS'][$key]));
     }
     /**
     * @return string
     */
     public function ip(): string
     {
         return $this->allData['SERVER']['REMOTE_ADDR'];
     }
     /**
     * @return string
     */
     public function userAgent(): string
     {
         return $this->allData['SERVER']['HTTP_USER_AGENT'];
     }
     /**
     * @return string
     */
     public function host(): string
     {
         return $this->allData['SERVER']['HTTP_HOST'];
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
         $uri = parse_url($this->allData['SERVER']['REQUEST_URI']);
         return $uri['path'];
     }
     /**
      *
      * @return string
      */
      public function uri(): string
      {
          return $this->allData['SERVER']['REQUEST_URI'];
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
         $uri = $this->allData['SERVER']['REQUEST_URI'];
         return str_replace('/?', '', $uri);
     }
     /**
     * @param string $key
     *
     * @return ?string
     */
     public function query(string $key): ?string
     {
         if(isset($this->allData['GET'][$key])) {
             return $this->allData['GET'][$key];
         }
         return null;
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
         return $this->scheme() .'://'. $this->host() . $this->path();
     }
     /**
     * @return bool
     */
     public function hasFile(string $key): bool
     {
         if (isset($_FILES['fileInputName']) && $_FILES['fileInputName']['error'] === UPLOAD_ERR_OK) {
             return true;
         }
         return false;
     }

     /**
     * @return string
     */
     public function fullUrlWithQuery(): string
     {
         return $this->scheme() .'://'. $this->host() . $this->uri();
     }
     /**
     * @return string
     */
     public function port(): string
     {
         return $this->allData['SERVER']['SERVER_PORT'];
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

}
