<?php

declare(strict_types=1);

namespace Dune\Cookie;

use Dune\Cookie\CookieContainer;
use Dune\Cookie\Cookie;
use Dune\Cookie\Config\CookieConfig;

class CookieLoader
{
    use CookieContainer;
    /**
     * \Dune\Routing\Router instance
     *
     * @var ?Router
     */
    protected ?Cookie $cookie = null;
    /**
     * calling router method
     * setting up router instance
     *
     */
    public function __construct(array $configs)
    {
        $this->__setUp();
        $config = new CookieConfig($configs);
        if(!$this->cookie) {
            $this->cookie = new Cookie($config);
        }
    }
      /**
       * returning the loaded router instance
       *
       * @return Session
       */
    public function load(): Cookie
    {
        return $this->cookie;
    }
}
