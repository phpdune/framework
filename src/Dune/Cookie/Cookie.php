<?php

declare(strict_types=1);

namespace Dune\Cookie\Cookie;

class Cookie implements CookiInterface
{
    /**
     *
     * @param string $key
     * @param mixed $value
     *
     * @return none
     */
    public static function set(string $key, mixed $value): void
    {
        self::setCookie($key, $value);
    }
    /**
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function get(string $key): ?string
    {
        return self::getCookie($key);
    }
    /**
     *
     * @param string $key
     *
     * @return none
     */
    public static function unset(string $key): void
    {
        self::unsetCookie($key);
    }
    /**
     *
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        return self::hasCookie($key);
    }
    /**
     *
     * @param none
     *
     * @return none
     */
    public static function flush(): void
    {
        return self::flushCookie();
    }
    /**
     *
     * @param none
     *
     * @return null|array
     */
    public static function all(): ?array
    {
        return self::allCookie();
    }
}
