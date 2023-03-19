<?php

declare(strict_types=1);

namespace Dune\Session;

class Session extends SessionHandler implements SessionInterface
{
    /**
     * @param  string  $key
     * @param  string  $value
     *
     * @return none
     */
    public static function set(string $key, string $value): void
    {
        self::setSession($key, $value);
    }
    /**
     * @param  string  $key
     * @param  string  $method
     * @param callable|string $action
     *
     * @return string|null
     */

    public static function get(string $key): ?string
    {
        return self::getSession($key);
    }
    /**
     * @param  string  $key
     *
     * @return array|null
     */
    public static function getAll(): ?array
    {
        return self::getAllSession();
    }
    /**
     * @param  string  $key
     *
     * @return none
     */
    public static function unset(string $key): void
    {
        self::unsetSession($key);
    }
    /**
     * @param none
     *
     * @return none
     */
    public static function flush(): void
    {
        self::flushSession();
    }
    /**
     * @param has
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        return self::sessionHas($key);
    }
}
