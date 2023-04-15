<?php

declare(strict_types=1);

namespace Dune\Cookie;

interface CookieInterface
{
    /**
     * set the cookie
     *
     * @param string $key
     * @param mixed $value
     *
     * @return none
     */
    public static function set(string $key, mixed $value): void;
    /**
     * get the cookie
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function get(string $key): mixed;
    /**
     * unset a cookie
     *
     * @param string $key
     *
     * @return bool
     */
    public static function unset(string $key): void;
    /**
     * check if the cookie exist by its key
     *
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool;
    /**
     * delete all cookies that are currently active
     *
     * @param none
     *
     * @return none
     */
    public static function flush(): void;
    /**
     * show all the cookies that are currently active
     *
     * @param none
     *
     * @return null|array
     */
    public static function all(): ?array;
}
