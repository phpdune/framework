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

use Symfony\Component\HttpFoundation\RedirectResponse;
use Dune\Http\Request;
use Dune\Http\ResponseInterface;
use Dune\Facades\Session;

class Redirect extends RedirectResponse implements ResponseInterface
{
    /**
     * \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @var RedirectResponse
     */
    public RedirectResponse $response;
    /**
     * Initializing var $response
     *
     * @param RedirectResponse $response
     */
    public function __construct(RedirectResponse $response)
    {
        $this->response = $response;
    }
    /**
     * redirect to path
     * example ('/test/uri')
     *
     * @param string $path
     * @param int $code
     *
     * @return self
     */
    public function path(string $path, int $code = 302): self
    {
        $this->response->setTargerUrl($path);
        $this->response->setStatusCode($code);
        $this->sendResponse();
        return $this;
    }
    /**
     * redirect to path
     * example ('/test/uri')
     *
     * @param string $route
     * @param array<string,mixed> $particles
     * @param int $code
     *
     * @return self
     */
    public function route(string $route, array $particles = [], int $code = 302): self
    {
        $url = \route($route, $particles);
        $this->response->setTargetUrl($url);
        $this->response->setStatusCode($code);
        $this->sendResponse();
        return $this;
    }
    /**
     * redirect to back
     *
     * @return self
     */
    public function back(): self
    {
        $request = new Request();
        $url = $request->header('referer');
        $this->response->setTargetUrl($url);
        $this->response->setStatusCode(302);
        $this->sendResponse();
        return $this;
    }
    /**
     * redirect with a session msg
     *
     * @param string $alert
     * @param $alertValue
     *
     * @return self
     */
    public function with(string $alert, mixed $alertValue): self
    {
        Session::set('__'.$alert, $alertValue);
        return $this;
    }
    /**
     * redirect with many session msg
     *
     * @param array<string,mixed>
     *
     * @return self
     */
    public function withArray(array $alerts): self
    {
        foreach ($alerts as $key => $value) {
            Session::set('__'.$key, $value);
        }
        return $this;
    }
      /**
       * set the response headers
       *
       * @param int $code
       *
       * @return self
       */
    public function withStatus(int $code = 302): self
    {
        $this->response->setCode($code);
        return $this;
    }
      /**
       * set the response headers
       *
       * @param array<string,string> $headers
       *
       * @return self
       */
    public function withHeader(array $headers): self
    {
        foreach($headers as $key => $value) {
            $this->response->headers->set($headers);
        }
        return $this;
    }
    /**
     * send the redirect response
     *
     * @return RedirectResponse
     */
    private function sendResponse(): RedirectResponse
    {
        return $this->response;
    }
      /**
       * access the method of symfony response
       *
       * @param string $method
       * @param array $args
       *
       * @return mixed
       */
       public function __call(string $method, array $args): mixed
       {
           return $this->response->$method(...$args);
       }
}
