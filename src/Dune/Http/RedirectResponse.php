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

use Dune\Http\Request;
use Dune\Http\ResponseInterface;
use Dune\Facades\Session;

class RedirectResponse implements ResponseInterface
{
    /**
     * redirect contents details
     * 
     * @var array<string,mixed>
     */
     private array $contents = [];
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
        $this->contents['uri'] = $path;
        $this->contents['status'] = $code;
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
        $uri = \route($route, $particles);
        $this->contents['uri'] = $uri;
        $this->contents['status'] = $code;
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
        $uri = $_SERVER['HTTP_REFERER'];
        $this->contents['uri'] = $uri;
        $this->contents['status'] = 302;
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
     * @param array<string,mixed> $alerts
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
        $this->contents['status'] = $code;
        return $this;
    }
    
    /**
     * send the redirect response
     *
     * @return int
     */
    private function sendResponse(): int
    {
        return response()->redirect($this->contents['path'],$this->contents['status']);
    }
    
}
