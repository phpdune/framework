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
     * Cookie config
     *
     * @var ?Cookie
     */
    protected ?Cookie $cookie = null;

    /**
     * calling router method
     * setting up router instance
     *
     * @param array<string, string|bool> $configs
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
     * @return Cookie
     */
    public function load(): Cookie
    {
        return $this->cookie;
    }
}
