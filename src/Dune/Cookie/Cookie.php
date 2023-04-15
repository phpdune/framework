<?php

declare(strict_types=1);

namespace Dune\Cookie;

use Dune\Cookie\CookieHandler;
use Dune\Cookie\CookieContainer;
use Dune\Cookie\Exception\InvalidMethod;

class Cookie implements CookieInterface
{
    use CookieContainer;

    /**
     * \Dune\Cookie\CookieHandler instance
     *
     * @var CookieHandler
     */
    private static ?CookieHandler $handler = null;
    /**
     *
     * @param string $key
     * @param mixed $value
     *
     * @return none
     */
    public static function set(string $key, mixed $value): void
    {
        self::init();
        self::$handler->setCookie($key, $value);
    }
    /**
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function get(string $key): mixed
    {
        self::init();
        return self::$handler->getCookie($key);
    }
    /**
     *
     * @param string $key
     *
     * @return none
     */
    public static function unset(string $key): void
    {
        self::init();
        self::$handler->unsetCookie($key);
    }
    /**
     *
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::init();
        return self::$handler->hasCookie($key);
    }
    /**
     *
     * @param none
     *
     * @return none
     */
    public static function flush(): void
    {
        self::init();
        self::$handler->flushCookie();
    }
    /**
     *
     * @param none
     *
     * @return null|array
     */
    public static function all(): ?array
    {
        self::init();
        return self::$handler->allCookie();
    }
    /**
     *
     * @param ?string $method
     * @param ?array $array
     *
     * @throw \Dune\Cookie\InvalidMethod
     *
     *  @return none
     */
     public static function __callStatic($method, $args)
     {
         if ($method == 'add' || $method == 'put') {
             self::set($args[0], $args[1]);
         } else {
             throw new InvalidMethod("Method {$method} is invalid");
         }
     }
}
