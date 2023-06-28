<?php

declare(strict_types=1);

namespace Dune\Cookie;

use Dune\Cookie\CookieInterface;
use Dune\Cookie\Config\CookieConfig;

class Cookie implements CookieInterface
{
    /**
     * cookie configuration
     *
     * @var CookieConfig
     */
    private CookieConfig $config;
    /**
     * cookie pattern regex
     *
     * @var const
     */
    private const COOKIE_PATTERN = "^[a-zA-Z0-9_\.]{1,64}$^";

    /**
     * cookie configuration setting
     *
     * @param CookieConfig $config
     */
    public function __construct(CookieConfig $config)
    {
        $this->config = $config;
    }
    /**
     * cookie setting handler
     *
     * @param string $key
     * @param string $value
     *
     */
    public function set(string $key, string $value): void
    {
        if ($this->validName($key)) {
            setcookie($key, $value, time() + $this->config->get('time'), $this->config->get('path'), $this->config->get('domain'), $this->config->get('secure'), $this->config->get('http_only'));
        }
    }
    /**
    * get the cookie handler
    *
    * @param string $key
    *
    * @return mixed
    */
    public function get(string $key): mixed
    {
        return (isset($_COOKIE[$key]) ? $_COOKIE[$key] : null);
    }
    /**
     * cookie unsetting handler
     *
     * @param string $key
     *
     */
    public function unset(string $key): void
    {
        if ($this->has($key)) {
            $params = session_get_cookie_params();
            setcookie(
                $key,
                '',
                time() - 3600,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
            unset($_COOKIE[$key]);
        }
    }

    /**
      * checking cookie available or not
      *
      * @param string $key
      *
      * @return bool
      */
    public function has(string $key): bool
    {
        return (isset($_COOKIE[$key]) ? true : false);
    }
    /**
     * delete all cookies that are currently active
     *
     */
    public function flush(): void
    {
        foreach ($_COOKIE as $key => $value) {
            $this->unset($key);
        }
    }
    /**
     * show all the cookies that are currently active
     *
     *
     * @return null|array<string,string>
     */
    public function all(): ?array
    {
        return $_COOKIE;
    }
    /**
     * Check cookie name is a valid one by regex
     *
     * @param  string  $key
     *
     * @return bool
     */
    private function validName(string $key): bool
    {
        return (preg_match(self::COOKIE_PATTERN, $key) === 1);
    }
}
