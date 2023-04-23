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
     * @var ?CookieHandler
     */
    private static ?CookieHandler $handler = null;
    /**
     *
     * @param string $key
     * @param string $value
     *
     */
    public static function set(string $key, string $value): void
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
     * flush method
     */
    public static function flush(): void
    {
        self::init();
        self::$handler->flushCookie();
    }
    /**
     *
     * @return null|array<string,mixed>
     */
    public static function all(): ?array
    {
        self::init();
        return self::$handler->allCookie();
    }
    /**
     *
     * @param ?string $method
     * @param ?array<string> $args
     *
     * @throw \Dune\Cookie\InvalidMethod
     *
     */
     public static function __callStatic(?string $method, ?array $args): void
     {
         $key = (isset($args[0]) ? $args[0] : '');
         $value = (isset($args[1]) ? $args[1] : '');
         if ($method == 'add' || $method == 'put') {
             self::set($key, $value);
         } else {
             throw new InvalidMethod("Method {$method} is invalid");
         }
     }
}
